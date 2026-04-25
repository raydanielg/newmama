@extends('layouts.admin')

@section('title', 'Payslips')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Employee Payslips</h3>
        <p>View and manage generated payslips from payroll runs.</p>
    </div>
</div>

<div class="content-card">
    <div class="card-header" style="padding:14px; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="margin:0;">Recent Payslips</h3>
        <form method="GET" action="{{ route('admin.hrm.payslips') }}" style="display:flex; gap:10px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Employee name or #..." style="padding:8px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <button class="btn-primary" type="submit">Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Period</th>
                    <th>Basic</th>
                    <th>Earnings</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payslips as $p)
                <tr>
                    <td style="font-weight:900;">
                        {{ $p->employee?->first_name }} {{ $p->employee?->last_name }}
                        <div style="font-size:11px; color:#6b7280;">{{ $p->employee?->employee_number }}</div>
                    </td>
                    <td>{{ $p->payrollRun?->period }}</td>
                    <td>{{ number_format((float)$p->basic_salary, 0) }}</td>
                    <td style="color:#166534;">+{{ number_format((float)$p->total_earnings, 0) }}</td>
                    <td style="color:#991b1b;">-{{ number_format((float)$p->total_deductions, 0) }}</td>
                    <td style="font-weight:900;">TSh {{ number_format((float)$p->net_pay, 0) }}</td>
                    <td>
                        <span class="badge" style="background:#f3f4f6; color:#111827; font-weight:900;">{{ strtoupper($p->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.hrm.payslip.show', $p) }}" class="btn-icon" style="text-decoration:none;">View/Print</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; padding:20px; color:#6b7280;">No payslips found. Generate payroll first.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:14px;">
        {{ $payslips->links() }}
    </div>
</div>
@endsection
