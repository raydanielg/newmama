@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $employee->full_name }}</h3>
        <p>{{ $employee->employee_number }} · {{ $employee->department ?: '—' }} · {{ $employee->role ?: '—' }}</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.employees') }}">Back</a>
        <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.payroll') }}">Payroll</a>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">{{ ucfirst($employee->employment_status) }}</h3>
            <p class="stat-label">Status</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">TSh {{ number_format((float) $employee->basic_salary, 2) }}</h3>
            <p class="stat-label">Basic Salary</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">{{ strtoupper($employee->pay_frequency) }}</h3>
            <p class="stat-label">Pay Frequency</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">TSh {{ number_format((float) $ytdNet, 2) }}</h3>
            <p class="stat-label">YTD Net Pay</p>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1.1fr 0.9fr; gap:14px;">
    <div class="content-card" style="padding:16px;">
        <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Employee Details</h3></div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Department</div>
                <div style="font-weight:800;">{{ $employee->department ?: '—' }}</div>
            </div>
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Role</div>
                <div style="font-weight:800;">{{ $employee->role ?: '—' }}</div>
            </div>
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Phone</div>
                <div style="font-weight:800;">{{ $employee->phone ?: '—' }}</div>
            </div>
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Email</div>
                <div style="font-weight:800;">{{ $employee->email ?: '—' }}</div>
            </div>
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Hire Date</div>
                <div style="font-weight:800;">{{ optional($employee->hire_date)->toDateString() ?: '—' }}</div>
            </div>
            <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">DOB</div>
                <div style="font-weight:800;">{{ optional($employee->dob)->toDateString() ?: '—' }}</div>
            </div>
        </div>

        <div style="margin-top:12px; display:flex; gap:8px; flex-wrap:wrap;">
            <span class="badge status-trying">Profile Complete</span>
            <span class="badge status-trying">Payroll Enabled</span>
            @if($employee->employment_status !== 'active')
                <span class="badge status-pregnant">Needs Review</span>
            @endif
        </div>

        @if($employee->address)
            <div style="margin-top:12px; padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Address</div>
                <div style="font-weight:700;">{{ $employee->address }}</div>
            </div>
        @endif
        @if($employee->notes)
            <div style="margin-top:12px; padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="color:#6b7280; font-size:12px;">Notes</div>
                <div style="font-weight:700;">{{ $employee->notes }}</div>
            </div>
        @endif
    </div>

    <div>
        <div class="content-card" style="padding:16px; margin-bottom:14px;">
            <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Recent Payslips</h3></div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th style="width:100px;">Period</th><th style="text-align:right; width:160px;">Net</th><th style="width:100px;"></th></tr></thead>
                    <tbody>
                        @forelse($latestPayslips as $p)
                            <tr>
                                <td style="font-family:var(--mono);">{{ optional($p->payrollRun)->period }}</td>
                                <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $p->net_pay, 2) }}</td>
                                <td style="text-align:right;"><a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.hrm.payslip.show', $p) }}">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; color:#6b7280; padding:14px;">No payslips yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-card" style="padding:16px;">
            <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Attendance (Last 14 Days)</h3></div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th style="width:120px;">Date</th><th style="width:110px;">Status</th><th>In</th><th>Out</th></tr></thead>
                    <tbody>
                        @forelse($recentAttendance as $a)
                            <tr>
                                <td style="font-family:var(--mono);">{{ optional($a->work_date)->toDateString() }}</td>
                                <td>
                                    <span class="badge {{ in_array($a->status, ['present','off'], true) ? 'status-trying' : 'status-pregnant' }}">{{ ucfirst($a->status) }}</span>
                                </td>
                                <td>{{ $a->clock_in ?: '—' }}</td>
                                <td>{{ $a->clock_out ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center; color:#6b7280; padding:14px;">No attendance logs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
