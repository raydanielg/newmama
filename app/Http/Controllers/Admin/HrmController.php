<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\PayrollComponent;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HrmController extends Controller
{
    public function employees(Request $request)
    {
        $query = Employee::query();

        if ($dept = trim((string) $request->query('department', ''))) {
            $query->where('department', 'like', "%{$dept}%");
        }

        if ($status = $request->query('status')) {
            if (in_array($status, ['active', 'inactive', 'terminated'], true)) {
                $query->where('employment_status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('first_name')->orderBy('last_name')->paginate(20)->withQueryString();

        $totalEmployees = (int) Employee::query()->count();
        $monthlyPayroll = (float) Employee::query()->where('employment_status', 'active')->sum('basic_salary');

        return view('admin.hrm.employees', [
            'title' => 'Employee Management',
            'employees' => $employees,
            'totalEmployees' => $totalEmployees,
            'monthlyPayroll' => $monthlyPayroll,
        ]);
    }

    public function employeeStore(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'dob' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'department' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:100'],
            'hire_date' => ['nullable', 'date'],
            'employment_status' => ['required', 'in:active,inactive,terminated'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'pay_frequency' => ['required', 'in:monthly,weekly,daily'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $employeeNo = 'EMP-' . str_pad((string) (Employee::query()->max('id') + 1), 5, '0', STR_PAD_LEFT);

        $emp = Employee::create(array_merge($data, [
            'employee_number' => $employeeNo,
        ]));

        return redirect()->route('admin.hrm.employee.show', $emp)->with('status', 'Employee created');
    }

    public function employeeShow(Employee $employee)
    {
        $latestPayslips = Payslip::query()
            ->with('payrollRun')
            ->where('employee_id', $employee->id)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $recentAttendance = AttendanceLog::query()
            ->where('employee_id', $employee->id)
            ->orderByDesc('work_date')
            ->limit(14)
            ->get();

        $ytdNet = (float) Payslip::query()
            ->where('employee_id', $employee->id)
            ->whereYear('created_at', now()->year)
            ->sum('net_pay');

        return view('admin.hrm.employee_show', [
            'title' => 'Employee Profile',
            'employee' => $employee,
            'latestPayslips' => $latestPayslips,
            'recentAttendance' => $recentAttendance,
            'ytdNet' => $ytdNet,
        ]);
    }

    public function payroll(Request $request)
    {
        $components = PayrollComponent::query()->orderBy('type')->orderBy('name')->get();
        $runs = PayrollRun::query()->orderByDesc('period_start')->paginate(20);

        return view('admin.hrm.payroll', [
            'title' => 'Payroll Management',
            'components' => $components,
            'runs' => $runs,
        ]);
    }

    public function payrollComponentStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:earning,deduction'],
            'calculation_type' => ['required', 'in:fixed,percent_basic'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        PayrollComponent::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'calculation_type' => $data['calculation_type'],
            'amount' => (float) ($data['amount'] ?? 0),
            'rate' => (float) ($data['rate'] ?? 0),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.hrm.payroll')->with('status', 'Component saved');
    }

    public function payrollComponentToggle(PayrollComponent $component)
    {
        $component->update(['is_active' => !$component->is_active]);

        return redirect()->route('admin.hrm.payroll')->with('status', 'Component updated');
    }

    public function payrollRun(Request $request)
    {
        $data = $request->validate([
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        $period = date('Y-m', strtotime($data['period_start']));

        $run = PayrollRun::firstOrCreate([
            'period' => $period,
        ], [
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'status' => 'draft',
        ]);

        DB::transaction(function () use ($run) {
            $employees = Employee::query()->where('employment_status', 'active')->get();
            $components = PayrollComponent::query()->where('is_active', true)->get();

            $runTotals = [
                'employee_count' => $employees->count(),
                'total_basic' => 0.0,
                'total_earnings' => 0.0,
                'total_deductions' => 0.0,
                'total_net' => 0.0,
            ];

            foreach ($employees as $emp) {
                $basic = (float) $emp->basic_salary;
                $earn = 0.0;
                $ded = 0.0;

                $payslip = Payslip::updateOrCreate([
                    'payroll_run_id' => $run->id,
                    'employee_id' => $emp->id,
                ], [
                    'basic_salary' => $basic,
                    'total_earnings' => 0,
                    'total_deductions' => 0,
                    'net_pay' => 0,
                    'status' => 'generated',
                ]);

                PayslipLine::query()->where('payslip_id', $payslip->id)->delete();

                foreach ($components as $c) {
                    $amount = 0.0;
                    if ($c->calculation_type === 'fixed') {
                        $amount = (float) $c->amount;
                    } elseif ($c->calculation_type === 'percent_basic') {
                        $amount = round($basic * ((float) $c->rate) / 100, 2);
                    }

                    if ($amount == 0.0) {
                        continue;
                    }

                    PayslipLine::create([
                        'payslip_id' => $payslip->id,
                        'component_name' => $c->name,
                        'type' => $c->type,
                        'amount' => $amount,
                    ]);

                    if ($c->type === 'earning') {
                        $earn += $amount;
                    } else {
                        $ded += $amount;
                    }
                }

                $net = $basic + $earn - $ded;

                $payslip->update([
                    'total_earnings' => $earn,
                    'total_deductions' => $ded,
                    'net_pay' => $net,
                ]);

                $runTotals['total_basic'] += $basic;
                $runTotals['total_earnings'] += $earn;
                $runTotals['total_deductions'] += $ded;
                $runTotals['total_net'] += $net;
            }

            $run->update($runTotals);
        });

        return redirect()->route('admin.hrm.payroll')->with('status', 'Payroll run generated: ' . $run->period);
    }

    public function payslips(Request $request)
    {
        $query = Payslip::query()->with(['employee', 'payrollRun'])->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        $payslips = $query->paginate(20)->withQueryString();

        return view('admin.hrm.payslips', compact('payslips'));
    }

    public function attendance(Request $request)
    {
        $date = $request->query('date') ?: now()->toDateString();

        $logs = AttendanceLog::query()
            ->with('employee')
            ->whereDate('work_date', $date)
            ->orderBy('employee_id')
            ->get();

        $employees = Employee::query()->orderBy('first_name')->orderBy('last_name')->get();

        return view('admin.hrm.attendance', [
            'title' => 'Attendance Tracking',
            'date' => $date,
            'logs' => $logs,
            'employees' => $employees,
        ]);
    }

    public function payslipShow(Payslip $payslip)
    {
        $payslip->load(['employee', 'payrollRun', 'lines']);

        return view('admin.hrm.payslip_show', [
            'title' => 'Payslip',
            'payslip' => $payslip,
        ]);
    }

    public function attendanceStore(Request $request)
    {
        $data = $request->validate([
            'work_date' => ['required', 'date'],
            'employee_id' => ['required', 'exists:employees,id'],
            'clock_in' => ['nullable'],
            'clock_out' => ['nullable'],
            'status' => ['required', 'in:present,late,absent,off'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        AttendanceLog::updateOrCreate([
            'employee_id' => $data['employee_id'],
            'work_date' => $data['work_date'],
        ], [
            'clock_in' => $data['clock_in'] ?? null,
            'clock_out' => $data['clock_out'] ?? null,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('admin.hrm.attendance', ['date' => $data['work_date']])->with('status', 'Attendance saved');
    }
}
