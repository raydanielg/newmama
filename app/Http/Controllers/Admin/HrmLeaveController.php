<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrmLeaveRequest;
use App\Models\HrmLeaveType;
use Illuminate\Http\Request;

class HrmLeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = HrmLeaveRequest::query()->with(['employee', 'leaveType', 'reviewer'])->orderByDesc('id');

        if ($status = $request->query('status')) {
            if (in_array($status, ['pending', 'approved', 'rejected', 'cancelled'], true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(20)->withQueryString();

        $types = HrmLeaveType::query()->orderBy('name')->get();
        $employees = Employee::query()->orderBy('first_name')->orderBy('last_name')->get();

        return view('admin.hrm.leave', [
            'title' => 'HRM Leave',
            'requests' => $requests,
            'types' => $types,
            'employees' => $employees,
        ]);
    }

    public function saveType(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20'],
            'default_days' => ['required', 'integer', 'min:0'],
            'requires_approval' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        HrmLeaveType::updateOrCreate([
            'code' => strtoupper(trim($data['code'])),
        ], [
            'name' => $data['name'],
            'default_days' => (int) $data['default_days'],
            'requires_approval' => $request->has('requires_approval'),
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('status', 'Leave type saved');
    }

    public function storeRequest(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type_id' => ['required', 'exists:hrm_leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string'],
        ]);

        $days = now()->parse($data['start_date'])->diffInDays(now()->parse($data['end_date'])) + 1;

        HrmLeaveRequest::create([
            'employee_id' => (int) $data['employee_id'],
            'leave_type_id' => (int) $data['leave_type_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days' => (float) $days,
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Leave request submitted');
    }

    public function decide(Request $request, HrmLeaveRequest $leaveRequest)
    {
        $data = $request->validate([
            'decision' => ['required', 'in:approve,reject,cancel'],
            'review_notes' => ['nullable', 'string'],
        ]);

        $status = match ($data['decision']) {
            'approve' => 'approved',
            'reject' => 'rejected',
            default => 'cancelled',
        };

        $leaveRequest->update([
            'status' => $status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $data['review_notes'] ?? null,
        ]);

        return back()->with('status', 'Leave request updated');
    }
}
