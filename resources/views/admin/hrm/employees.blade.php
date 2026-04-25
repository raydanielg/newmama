@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Manage employees, payroll and attendance.</p>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">{{ (int) $totalEmployees }}</h3>
            <p class="stat-label">Total Employees</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">TSh {{ number_format((float) $monthlyPayroll, 0) }}</h3>
            <p class="stat-label">Monthly Basic Payroll</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">{{ (int) $employees->total() }}</h3>
            <p class="stat-label">Shown (Filtered)</p>
        </div>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.employees') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search name/number/phone/email" style="flex:1; min-width:260px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input name="department" value="{{ request('department') }}" placeholder="Department" style="min-width:180px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <option value="">All Status</option>
            @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'terminated' => 'Terminated'] as $k => $v)
                <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.employees') }}">Reset</a>
    </form>

    <div style="font-weight:800; margin-bottom:10px;">Add New Employee</div>
    <form method="POST" action="{{ route('admin.hrm.employee.store') }}" style="display:grid; grid-template-columns: 180px 180px 160px 1fr 180px 160px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">First Name</label>
            <input name="first_name" value="{{ old('first_name') }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Last Name</label>
            <input name="last_name" value="{{ old('last_name') }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="employment_status" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'terminated' => 'Terminated'] as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Department</label>
            <input name="department" value="{{ old('department') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Role</label>
            <input name="role" value="{{ old('role') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Basic Salary</label>
            <input type="number" step="0.01" min="0" name="basic_salary" value="{{ old('basic_salary', 0) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono); font-weight:800;">
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input name="phone" value="{{ old('phone') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Hire Date</label>
            <input type="date" name="hire_date" value="{{ old('hire_date') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Pay Frequency</label>
            <select name="pay_frequency" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="monthly">Monthly</option>
                <option value="weekly">Weekly</option>
                <option value="daily">Daily</option>
            </select>
        </div>
        <div style="grid-column: 5 / span 2;">
            <button class="btn-primary" type="submit" style="width:100%;">Create Employee</button>
        </div>
        <div style="grid-column: 1 / -1;">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('notes') }}</textarea>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Employee Directory</h3></div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th style="width:140px;">Number</th>
                    <th>Role</th>
                    <th style="width:160px;">Department</th>
                    <th style="width:160px; text-align:right;">Basic Salary</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:160px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $e)
                    <tr>
                        <td>{{ $e->full_name }}</td>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $e->employee_number }}</td>
                        <td>{{ $e->role }}</td>
                        <td>{{ $e->department }}</td>
                        <td style="text-align:right; font-family:var(--mono);">TSh {{ number_format((float) $e->basic_salary, 2) }}</td>
                        <td>
                            <span class="badge {{ $e->employment_status === 'active' ? 'status-trying' : 'status-pregnant' }}">{{ ucfirst($e->employment_status) }}</span>
                        </td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.hrm.employee.show', $e) }}">Profile</a>
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.payroll') }}">Payroll</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; color:#6b7280; padding:18px;">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $employees->links() }}</div>
</div>
@endsection
