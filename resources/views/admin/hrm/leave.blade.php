@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<style>
    .split { display:grid; grid-template-columns: 1fr 420px; gap: 16px; align-items:start; }
    .card-pad { padding: 16px; }
</style>

<div class="module-header">
    <div class="header-info">
        <h3>HRM Leave</h3>
        <p>Manage leave types and employee leave requests.</p>
    </div>
</div>

@if(session('status'))
    <div class="content-card" style="padding:12px; border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; margin-bottom:14px;">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="content-card" style="padding:12px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; margin-bottom:14px;">
        <div style="font-weight:900; margin-bottom:6px;">Please fix the errors below:</div>
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="split">
    <div>
        <div class="content-card">
            <div class="card-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px;">
                <h3 style="margin:0;">Leave Requests</h3>
                <form method="GET" action="{{ route('admin.hrm.leave') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <input name="q" value="{{ request('q') }}" type="text" placeholder="Search employee..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:220px;">
                    <select name="status" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">All Status</option>
                        @foreach(['pending','approved','rejected','cancelled'] as $st)
                            <option value="{{ $st }}" {{ request('status')===$st ? 'selected' : '' }}>{{ strtoupper($st) }}</option>
                        @endforeach
                    </select>
                    <button class="btn-primary" type="submit">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th style="width:220px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                        <tr>
                            <td style="font-weight:900;">{{ $r->employee?->first_name }} {{ $r->employee?->last_name }}
                                <div style="font-size:12px; color:#6b7280;">{{ $r->employee?->employee_number }}</div>
                            </td>
                            <td>{{ $r->leaveType?->name }}</td>
                            <td>{{ $r->start_date?->format('M d') }} - {{ $r->end_date?->format('M d, Y') }}</td>
                            <td>{{ rtrim(rtrim(number_format((float)$r->days, 2, '.', ''), '0'), '.') }}</td>
                            <td>
                                @php($st = $r->status)
                                @if($st === 'approved')
                                    <span class="badge" style="background:#dcfce7; color:#166534; font-weight:900;">APPROVED</span>
                                @elseif($st === 'rejected')
                                    <span class="badge" style="background:#fee2e2; color:#991b1b; font-weight:900;">REJECTED</span>
                                @elseif($st === 'cancelled')
                                    <span class="badge" style="background:#f3f4f6; color:#111827; font-weight:900;">CANCELLED</span>
                                @else
                                    <span class="badge" style="background:#fef3c7; color:#92400e; font-weight:900;">PENDING</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.hrm.leave.decide', $r) }}" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                    @csrf
                                    <input type="text" name="review_notes" placeholder="Notes..." style="padding:8px 10px; border:1px solid #e5e7eb; border-radius:10px; min-width:160px;">
                                    <button class="btn-icon" name="decision" value="approve" type="submit">Approve</button>
                                    <button class="btn-icon" name="decision" value="reject" type="submit">Reject</button>
                                    <button class="btn-icon" name="decision" value="cancel" type="submit">Cancel</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center; padding:20px; color:#6b7280;">No leave requests.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:16px; padding: 0 14px 14px;">{{ $requests->links() }}</div>
        </div>
    </div>

    <div>
        <div class="content-card card-pad" style="margin-bottom:16px;">
            <h3 style="margin-top:0;">Create Leave Request</h3>
            <form method="POST" action="{{ route('admin.hrm.leave.requests.store') }}">
                @csrf
                <div style="display:grid; gap:10px;">
                    <div>
                        <label style="display:block; font-weight:900; margin-bottom:6px;">Employee *</label>
                        <select name="employee_id" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                            <option value="">— Select Employee —</option>
                            @foreach($employees as $e)
                                <option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }} ({{ $e->employee_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-weight:900; margin-bottom:6px;">Leave Type *</label>
                        <select name="leave_type_id" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                            <option value="">— Select Type —</option>
                            @foreach($types as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                        <div>
                            <label style="display:block; font-weight:900; margin-bottom:6px;">Start *</label>
                            <input type="date" name="start_date" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:900; margin-bottom:6px;">End *</label>
                            <input type="date" name="end_date" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        </div>
                    </div>

                    <div>
                        <label style="display:block; font-weight:900; margin-bottom:6px;">Reason</label>
                        <input name="reason" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    </div>

                    <div>
                        <button class="btn-primary" type="submit">Submit Request</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="content-card card-pad">
            <h3 style="margin-top:0;">Leave Types</h3>
            <form method="POST" action="{{ route('admin.hrm.leave.types.save') }}">
                @csrf
                <div style="display:grid; gap:10px;">
                    <div>
                        <label style="display:block; font-weight:900; margin-bottom:6px;">Type Name *</label>
                        <input name="name" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:900; margin-bottom:6px;">Code *</label>
                        <input name="code" required placeholder="e.g. AL" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    </div>
                    <div>
                        <label style="display:block; font-weight:900; margin-bottom:6px;">Default Days</label>
                        <input type="number" min="0" name="default_days" value="0" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    </div>

                    <label style="display:flex; gap:10px; align-items:center; font-weight:900;">
                        <input type="checkbox" name="requires_approval" value="1" checked>
                        Requires Approval
                    </label>
                    <label style="display:flex; gap:10px; align-items:center; font-weight:900;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Active
                    </label>

                    <button class="btn-primary" type="submit">Save Leave Type</button>
                </div>
            </form>

            <div style="margin-top:14px; font-size:13px; color:#6b7280;">
                Existing types:
                <div style="margin-top:6px; display:flex; gap:8px; flex-wrap:wrap;">
                    @foreach($types as $t)
                        <span class="badge" style="background:#f3f4f6; color:#111827; font-weight:900;">{{ $t->code }} - {{ $t->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
