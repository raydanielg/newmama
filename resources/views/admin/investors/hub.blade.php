@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Track recent activity and totals for the selected date range.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors') }}">Overview</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.portfolio') }}">Portfolio</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.reports') }}">Reports</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.investors.hub') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
        <div>
            <label class="form-label">From</label>
            <input type="date" name="from" value="{{ $from }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">To</label>
            <input type="date" name="to" value="{{ $to }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <button class="btn-primary" type="submit">Load</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.hub') }}">This Month</a>
    </form>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $totals['inflows'], 2) }}</h3><p class="stat-label">Contributions</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $totals['outflows'], 2) }}</h3><p class="stat-label">Withdrawals</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $totals['dividends'], 2) }}</h3><p class="stat-label">Dividends</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $recent->count() }}</h3><p class="stat-label">Recent Transactions</p></div></div>
</div>

<div class="content-card" style="padding:16px;">
    <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Recent Activity</h3></div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:120px;">Date</th>
                    <th>Investor</th>
                    <th style="width:140px;">Type</th>
                    <th>Description</th>
                    <th style="width:180px; text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $r)
                    <tr>
                        <td style="font-family:var(--mono);">{{ optional($r->posting_date)->toDateString() }}</td>
                        <td>
                            @if($r->investor)
                                <a style="text-decoration:none;" href="{{ route('admin.investors.show', $r->investor) }}">{{ $r->investor->name }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ strtoupper($r->type) }}</td>
                        <td>{{ $r->description ?: ($r->reference ?: '—') }}</td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $r->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No transactions in this range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
