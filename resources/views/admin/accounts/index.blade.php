@extends('layouts.admin')

@section('title', 'Chart of Accounts')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Chart of Accounts</h3>
        <p>Manage your general ledger accounts.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.accounts.create') }}" class="btn-primary" style="text-decoration:none;">Add Account</a>
    </div>
</div>

<div class="content-card" style="padding: 16px;">
    <form method="GET" action="{{ route('admin.chart-of-accounts') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search code/name/category" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <select name="type" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            @php($types = ['all' => 'All Types', 'asset' => 'Asset', 'liability' => 'Liability', 'equity' => 'Equity', 'revenue' => 'Revenue', 'expense' => 'Expense', 'cogs' => 'COGS'])
            @foreach($types as $k => $label)
                <option value="{{ $k }}" {{ request('type','all') === $k ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.chart-of-accounts') }}">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:90px;">Code</th>
                    <th>Name</th>
                    <th style="width:120px;">Type</th>
                    <th style="width:160px;">Category</th>
                    <th style="width:160px; text-align:right;">Balance</th>
                    <th style="width:90px;">Active</th>
                    <th style="width:110px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $a)
                    <tr>
                        <td style="font-family: var(--mono); font-weight:700;">{{ $a->code }}</td>
                        <td>{{ $a->name }}</td>
                        <td>{{ ucfirst($a->type) }}</td>
                        <td>{{ $a->category }}</td>
                        <td style="text-align:right; font-family: var(--mono);">{{ number_format((float) $a->balance, 2) }}</td>
                        <td>{{ $a->is_active ? 'Yes' : 'No' }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.accounts.edit', $a) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $accounts->links() }}</div>
</div>
@endsection
