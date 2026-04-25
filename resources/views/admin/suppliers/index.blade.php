@extends('layouts.admin')

@section('title', 'Suppliers')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Suppliers</h3>
        <p>AP · Vendor management.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.suppliers.create') }}" class="btn-primary" style="text-decoration:none;">Add Supplier</a>
    </div>
</div>

<div class="content-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; padding: 14px;">
        <form method="GET" action="{{ route('admin.suppliers') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, code, contact..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">
            <select name="active" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="all" {{ $filterActive === 'all' ? 'selected' : '' }}>All</option>
                <option value="active" {{ $filterActive === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $filterActive === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn-primary" type="submit">Filter</button>
        </form>

        <div style="display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
            <div style="font-size:12px; color:#6b7280;">Total Suppliers: <strong>{{ number_format($suppliers->total()) }}</strong></div>
            <div style="font-size:12px; color:#6b7280;">Total AP Balance: <strong>{{ number_format($totalBalance, 2) }}</strong></div>
            <div style="font-size:12px; color:#6b7280;">Filtered AP Balance: <strong>{{ number_format($filteredBalance, 2) }}</strong></div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Supplier Name</th>
                    <th>Contact</th>
                    <th>Phone</th>
                    <th>Terms</th>
                    <th class="td-right">AP Balance (TZS)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->code }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $supplier->name }}</div>
                        @if($supplier->email)
                            <div style="font-size:12px; color:#6b7280;">{{ $supplier->email }}</div>
                        @endif
                    </td>
                    <td>{{ $supplier->contact_person ?? '-' }}</td>
                    <td>{{ $supplier->phone ?? '-' }}</td>
                    <td>{{ $supplier->payment_terms ?? 'NET30' }}</td>
                    <td class="td-right" style="font-weight:700; {{ (float) $supplier->balance_tzs > 0 ? 'color:#b91c1c;' : '' }}">
                        {{ (float) $supplier->balance_tzs > 0 ? number_format((float) $supplier->balance_tzs, 2) : '—' }}
                    </td>
                    <td>
                        <a class="btn-icon" href="{{ route('admin.suppliers.ledger', $supplier) }}">Ledger</a>
                        <a class="btn-icon" href="{{ route('admin.suppliers.edit', $supplier) }}">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 18px;">No suppliers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
