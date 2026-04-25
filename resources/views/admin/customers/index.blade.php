@extends('layouts.admin')

@section('title', 'Customers')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Customers</h3>
        <p>Manage cash customers and debtors.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.customers.create', ['type' => $type]) }}" class="btn-primary" style="text-decoration:none;">Add Customer</a>
    </div>
</div>

<div class="content-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; padding: 14px;">
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <a href="{{ route('admin.customers', array_filter(['type' => 'cash', 'q' => request('q'), 'segment' => request('segment')])) }}" class="btn-icon" style="text-decoration:none; {{ $type === 'cash' ? 'font-weight:700;' : '' }}">Cash</a>
            <a href="{{ route('admin.customers', array_filter(['type' => 'debtor', 'q' => request('q'), 'segment' => request('segment')])) }}" class="btn-icon" style="text-decoration:none; {{ $type === 'debtor' ? 'font-weight:700;' : '' }}">Debtors</a>
        </div>

        <form method="GET" action="{{ route('admin.customers') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, number, WhatsApp..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">
            <select name="segment" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="all" {{ request('segment','all') === 'all' ? 'selected' : '' }}>All segments</option>
                <option value="retail" {{ request('segment') === 'retail' ? 'selected' : '' }}>Retail</option>
                <option value="wholesale" {{ request('segment') === 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                <option value="corporate" {{ request('segment') === 'corporate' ? 'selected' : '' }}>Corporate</option>
            </select>
            <button class="btn-primary" type="submit">Filter</button>
        </form>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">{{ number_format($customers->total()) }}</h3>
            <p class="stat-label">Total {{ $type === 'debtor' ? 'Debtors' : 'Cash Customers' }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">{{ number_format($totalBalance, 2) }}</h3>
            <p class="stat-label">Total Balance</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">{{ number_format($totalCredit, 2) }}</h3>
            <p class="stat-label">Total Credit Limit</p>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Name</th>
                    <th>Segment</th>
                    <th>WhatsApp</th>
                    <th>Email</th>
                    <th class="td-right">Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->customer_number }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->segment ? ucfirst($customer->segment) : '-' }}</td>
                    <td>{{ $customer->whatsapp ?? '-' }}</td>
                    <td>{{ $customer->email ?? '-' }}</td>
                    <td class="td-right">{{ number_format((float) $customer->balance, 2) }}</td>
                    <td>
                        <a class="btn-icon" href="{{ route('admin.customers.ledger', $customer) }}">Ledger</a>
                        <a class="btn-icon" href="{{ route('admin.customers.edit', $customer) }}">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 18px;">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $customers->links() }}
    </div>
</div>
@endsection
