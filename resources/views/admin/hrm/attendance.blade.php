@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Attendance Logs</h3>
        <p>Monitor daily employee clock-in and clock-out times.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.employees') }}">Employees</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.attendance') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end; margin-bottom:14px;">
        <div>
            <label class="form-label">Work Date</label>
            <input type="date" name="date" value="{{ $date }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <button class="btn-primary" type="submit">Load</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.attendance') }}">Today</a>
    </form>

    <div style="font-weight:800; margin-bottom:10px;">Record Attendance</div>
    <form method="POST" action="{{ route('admin.hrm.attendance.store') }}" style="display:grid; grid-template-columns: 1fr 140px 140px 140px 1fr 140px; gap:10px; align-items:end;">
        @csrf
        <input type="hidden" name="work_date" value="{{ $date }}">
        <div>
            <label class="form-label">Employee</label>
            <select name="employee_id" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">— Select employee —</option>
                @foreach($employees as $e)
                    <option value="{{ $e->id }}">{{ $e->full_name }} ({{ $e->employee_number }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Clock In</label>
            <input type="time" name="clock_in" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Clock Out</label>
            <input type="time" name="clock_out" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="present">Present</option>
                <option value="late">Late</option>
                <option value="absent">Absent</option>
                <option value="off">Off</option>
            </select>
        </div>
        <div>
            <label class="form-label">Notes</label>
            <input name="notes" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Attendance ({{ $date }})</h3></div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th style="width:140px;">Clock In</th>
                    <th style="width:140px;">Clock Out</th>
                    <th style="width:120px;">Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $l)
                    <tr>
                        <td>{{ optional($l->employee)->full_name ?? '—' }}</td>
                        <td>{{ $l->clock_in ?: '—' }}</td>
                        <td>{{ $l->clock_out ?: '—' }}</td>
                        <td>
                            <span class="badge {{ in_array($l->status, ['present','off'], true) ? 'status-trying' : 'status-pregnant' }}">{{ ucfirst($l->status) }}</span>
                        </td>
                        <td>{{ $l->notes }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No attendance logs for this date.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
