@extends('layouts.admin')

@section('title', 'Expense Tracking')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Expense Management</h3>
        <p>Record and track all business expenditures.</p>
    </div>
    <div class="header-actions">
        <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.cash-payment.create') }}">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Record Expense
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Expense Logs</h3>
    </div>

    <div style="padding:16px; padding-top:0;">
        <form method="GET" action="{{ route('admin.expenses') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
            <input name="q" value="{{ request('q') }}" placeholder="Search ref/description/method" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <input type="date" name="from" value="{{ request('from') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <input type="date" name="to" value="{{ request('to') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            <button class="btn-primary" type="submit">Filter</button>
            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.expenses') }}">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th style="width:160px;">Reference</th>
                    <th style="width:160px; text-align:right;">Amount</th>
                    <th style="width:160px;">Payment Method</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                    <tr>
                        <td>{{ optional($v->posting_date)->toDateString() }}</td>
                        <td>{{ $v->description }}</td>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $v->ref }}</td>
                        <td style="text-align:right; font-family:var(--mono);">TSh {{ number_format((float) $v->total_amount, 2) }}</td>
                        <td>{{ $v->payment_method }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.vouchers.view', $v) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#6b7280; padding:18px;">No expenses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px; padding-top:0;">{{ $vouchers->links() }}</div>
</div>
@endsection
