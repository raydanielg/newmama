@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Import Orders</h3>
        <p>Goods receiving (stock-in) with CSV preview and posting.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.imports.import-order.create') }}" class="btn-primary" style="text-decoration:none;">New Import</a>
    </div>
</div>

@if(session('status'))
    <div class="content-card" style="padding:12px; border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; margin-bottom:14px;">{{ session('status') }}</div>
@endif

<div class="content-card">
    <div class="card-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px;">
        <h3 style="margin:0;">History</h3>
        <form method="GET" action="{{ route('admin.imports.import-order.index') }}" style="display:flex; gap:10px; align-items:center;">
            <input name="q" value="{{ request('q') }}" type="text" placeholder="Search ref or supplier..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:260px;">
            <button class="btn-primary" type="submit">Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Posting Date</th>
                    <th>Supplier</th>
                    <th>Total Lines</th>
                    <th>Total Cost</th>
                    <th>Created</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td style="font-family:var(--mono); font-weight:900;">{{ $o->ref }}</td>
                    <td>{{ $o->posting_date?->format('M d, Y') }}</td>
                    <td>{{ $o->supplier?->name ?: ($o->supplier_name ?: '—') }}</td>
                    <td>{{ $o->total_lines }}</td>
                    <td>TSh {{ number_format((float)$o->total_cost, 0) }}</td>
                    <td>{{ $o->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a class="btn-icon" href="{{ route('admin.imports.import-order.show', $o) }}" style="text-decoration:none;">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:20px; color:#6b7280;">No import orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px; padding: 0 14px 14px;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
