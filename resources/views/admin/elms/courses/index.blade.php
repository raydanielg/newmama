@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>ELMS Courses</h3>
        <p>Courses catalog · pricing · additional fees.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.courses.create') }}" class="btn-primary" style="text-decoration:none;">Add Course</a>
    </div>
</div>

<div class="content-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; padding: 14px;">
        <form method="GET" action="{{ route('admin.elms.courses') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title, code, category..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">
            <select name="category" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="active" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="all" {{ $filterActive === 'all' ? 'selected' : '' }}>All</option>
                <option value="active" {{ $filterActive === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $filterActive === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn-primary" type="submit">Filter</button>
        </form>

        <div style="display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
            <div style="font-size:12px; color:#6b7280;">Total Courses: <strong>{{ number_format($courses->total()) }}</strong></div>
            <div style="font-size:12px; color:#6b7280;">Total Base Price: <strong>{{ number_format($totalBase, 2) }}</strong></div>
            <div style="font-size:12px; color:#6b7280;">Filtered Base Price: <strong>{{ number_format($filteredBase, 2) }}</strong></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:110px;">Code</th>
                    <th>Course</th>
                    <th style="width:140px;">Category</th>
                    <th style="width:120px;">Level</th>
                    <th class="td-right" style="width:160px;">Base Price</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:210px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $c)
                <tr>
                    <td style="font-family:var(--mono); font-weight:800;">{{ $c->code }}</td>
                    <td>
                        <div style="font-weight:700;">{{ $c->title }}</div>
                        <div style="font-size:12px; color:#6b7280;">{{ $c->duration_hours ? $c->duration_hours . ' hrs' : '—' }}</div>
                    </td>
                    <td>{{ $c->category ?: '—' }}</td>
                    <td>{{ $c->level ?: '—' }}</td>
                    <td class="td-right" style="font-weight:800; font-family:var(--mono);">{{ $c->currency }} {{ number_format((float) $c->base_price, 2) }}</td>
                    <td>
                        <span class="badge {{ $c->is_active ? 'status-trying' : 'status-pregnant' }}">{{ $c->is_active ? 'ACTIVE' : 'INACTIVE' }}</span>
                    </td>
                    <td>
                        <a class="btn-icon" href="{{ route('admin.elms.courses.show', $c) }}">View</a>
                        <a class="btn-icon" href="{{ route('admin.elms.courses.edit', $c) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.elms.courses.toggle-status', $c) }}" style="display:inline;">
                            @csrf
                            <button class="btn-icon" type="submit">{{ $c->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 18px;">No courses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $courses->links() }}
    </div>
</div>
@endsection
