@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Create loyalty accounts and adjust points.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.hub') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.crm.loyalty') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search customer" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.crm.loyalty') }}">Reset</a>
    </form>

    <form method="POST" action="{{ route('admin.crm.loyalty.accounts') }}" style="display:grid; grid-template-columns: 1fr 160px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Create / Ensure Loyalty Account</label>
            <select name="customer_id" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">— Select customer —</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="btn-primary" type="submit">Create</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="POST" action="{{ route('admin.crm.loyalty.adjust') }}" style="display:grid; grid-template-columns: 260px 160px 160px 160px 1fr 140px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Account</label>
            <select name="crm_loyalty_account_id" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">— Select account —</option>
                @foreach($accounts as $a)
                    <option value="{{ $a->id }}">{{ optional($a->customer)->name }} · {{ number_format((float) $a->points_balance, 2) }} pts</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Date</label>
            <input type="date" name="posting_date" value="{{ now()->toDateString() }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Type</label>
            <select name="type" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="earn">EARN</option>
                <option value="redeem">REDEEM</option>
                <option value="adjust">ADJUST</option>
            </select>
        </div>
        <div>
            <label class="form-label">Points</label>
            <input type="number" step="0.01" name="points" value="0" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono);">
        </div>
        <div>
            <label class="form-label">Description</label>
            <input name="description" placeholder="Optional" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <button class="btn-primary" type="submit">Post</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th style="width:160px; text-align:right;">Points Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $a)
                    <tr>
                        <td>
                            @if($a->customer)
                                <a style="text-decoration:none;" href="{{ route('admin.customers.ledger', $a->customer) }}">{{ $a->customer->name }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $a->points_balance, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="text-align:center; color:#6b7280; padding:18px;">No loyalty accounts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $accounts->links() }}</div>
</div>
@endsection
