@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>ELMS Categories</h3>
        <p>Separate ELMS categories (not the public site articles).</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.categories.create') }}" class="btn-primary" style="text-decoration:none;">Add Category</a>
    </div>
</div>

<div class="content-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; padding: 14px;">
        <form method="GET" action="{{ route('admin.elms.categories') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or slug..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">
            <select name="active" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="all" {{ $filterActive === 'all' ? 'selected' : '' }}>All</option>
                <option value="active" {{ $filterActive === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $filterActive === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn-primary" type="submit">Filter</button>
        </form>

        <div style="font-size:12px; color:#6b7280;">Total Categories: <strong>{{ number_format($categories->total()) }}</strong></div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width:220px;">Slug</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:210px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>
                        <div style="font-weight:800;">{{ $cat->name }}</div>
                        <div style="font-size:12px; color:#6b7280;">{{ $cat->description ? \Illuminate\Support\Str::limit($cat->description, 80) : '—' }}</div>
                    </td>
                    <td style="font-family:var(--mono);">{{ $cat->slug }}</td>
                    <td>
                        <span class="badge {{ $cat->is_active ? 'status-trying' : 'status-pregnant' }}">{{ $cat->is_active ? 'ACTIVE' : 'INACTIVE' }}</span>
                    </td>
                    <td>
                        <a class="btn-icon" href="{{ route('admin.elms.categories.edit', $cat) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.elms.categories.toggle-status', $cat) }}" style="display:inline;">
                            @csrf
                            <button class="btn-icon" type="submit">{{ $cat->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 18px;">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $categories->links() }}
    </div>
</div>
@endsection
