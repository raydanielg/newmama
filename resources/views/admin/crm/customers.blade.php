@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Customer list for CRM operations (loyalty, inbox, feedback, pre-orders).</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.customers') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <input name="q" value="{{ $q }}" placeholder="Search name/number/phone" style="flex:1; min-width:240px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Search</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.customers') }}">Reset</a>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th style="width:160px;">Number</th>
                    <th style="width:160px;">Phone</th>
                    <th style="width:160px; text-align:right;">Balance</th>
                    <th style="width:160px; text-align:right;">Loyalty Points</th>
                    <th style="width:160px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                    @php($la = $loyalty[$c->id] ?? null)
                    <tr>
                        <td><a style="text-decoration:none;" href="{{ route('admin.customers.ledger', $c) }}">{{ $c->name }}</a></td>
                        <td style="font-family:var(--mono);">{{ $c->customer_number }}</td>
                        <td>{{ $c->phone ?: $c->whatsapp }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $c->balance, 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) optional($la)->points_balance, 2) }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.customers.ledger', $c) }}">Profile</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; color:#6b7280; padding:18px;">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $customers->links() }}</div>
</div>
@endsection
