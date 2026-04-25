@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Income statement for selected period.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.reports.pnl') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <input type="date" name="from" value="{{ $from }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="to" value="{{ $to }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Run</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports.pnl') }}">Reset</a>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom:12px;">
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
            <div style="font-size:11px; color:#6b7280;">Revenue</div>
            <div style="font-family:var(--mono); font-weight:900; font-size:20px;">TSh {{ number_format((float) $totals['revenue'], 2) }}</div>
        </div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
            <div style="font-size:11px; color:#6b7280;">COGS</div>
            <div style="font-family:var(--mono); font-weight:900; font-size:20px;">TSh {{ number_format((float) $totals['cogs'], 2) }}</div>
        </div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
            <div style="font-size:11px; color:#6b7280;">Gross Profit</div>
            <div style="font-family:var(--mono); font-weight:900; font-size:20px;">TSh {{ number_format((float) $totals['gross'], 2) }}</div>
        </div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;">
            <div style="font-size:11px; color:#6b7280;">Expenses</div>
            <div style="font-family:var(--mono); font-weight:900; font-size:20px;">TSh {{ number_format((float) $totals['expense'], 2) }}</div>
        </div>
    </div>

    <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px; background:#f9fafb; margin-bottom:14px;">
        <div style="font-size:11px; color:#6b7280;">Net Profit</div>
        <div style="font-family:var(--mono); font-weight:900; font-size:24px;">TSh {{ number_format((float) $totals['net'], 2) }}</div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:120px;">Code</th>
                    <th>Account</th>
                    <th style="width:120px;">Type</th>
                    <th style="width:180px; text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $r['code'] }}</td>
                        <td>{{ $r['name'] }}</td>
                        <td>{{ strtoupper($r['type']) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">TSh {{ number_format((float) $r['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#6b7280; padding:18px;">No P&amp;L activity found for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
