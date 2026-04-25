@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>ELMS Trainers</h3>
        <p>Manage teachers and instructors for your courses.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.trainers.create') }}" class="btn-primary" style="text-decoration:none;">Add Trainer</a>
    </div>
</div>

@if(session('status'))
    <div class="content-card" style="padding:12px; border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; margin-bottom:14px;">{{ session('status') }}</div>
@endif

<div class="content-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; padding: 14px;">
        <form method="GET" action="{{ route('admin.elms.trainers.index') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, email, specialty..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">
            <select name="active" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="all" {{ $filterActive === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="active" {{ $filterActive === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $filterActive === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn-primary" type="submit">Filter</button>
        </form>
        <div style="font-size:12px; color:#6b7280;">Total Trainers: <strong>{{ number_format($trainers->total()) }}</strong></div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Trainer</th>
                    <th>Specialization</th>
                    <th>Contact</th>
                    <th style="width:100px;">Courses</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainers as $t)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:36px; height:36px; border-radius:50%; background:#e5e7eb; display:flex; align-items:center; justify-content:center; font-weight:800; color:#4b5563; overflow:hidden;">
                                @if($t->photo_url)
                                    <img src="{{ $t->photo_url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    {{ strtoupper(substr($t->name, 0, 1)) }}
                                @endif
                            </div>
                            <div style="font-weight:700;">{{ $t->name }}</div>
                        </div>
                    </td>
                    <td>{{ $t->specialization ?: '—' }}</td>
                    <td>
                        <div style="font-size:13px;">{{ $t->email ?: '—' }}</div>
                        <div style="font-size:12px; color:#6b7280;">{{ $t->phone ?: '' }}</div>
                    </td>
                    <td style="text-align:center;"><span class="badge" style="background:#f3f4f6; color:#374151;">{{ $t->courses_count }}</span></td>
                    <td>
                        <span class="badge {{ $t->is_active ? 'status-trying' : 'status-pregnant' }}">{{ $t->is_active ? 'ACTIVE' : 'INACTIVE' }}</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <a class="btn-ico-circle edit" href="{{ route('admin.elms.trainers.edit', $t) }}" title="Edit">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.elms.trainers.toggle-status', $t) }}" style="display:inline;">
                                @csrf
                                <button class="btn-ico-circle" type="submit" title="{{ $t->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($t->is_active)
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    @endif
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.elms.trainers.destroy', $t) }}" style="display:inline;" onsubmit="return confirm('Delete this trainer?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn-ico-circle delete" type="submit" title="Delete">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#6b7280;">No trainers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:16px;">{{ $trainers->links() }}</div>
</div>
@endsection
