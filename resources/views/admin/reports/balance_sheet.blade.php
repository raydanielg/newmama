@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Statement of financial position as at selected date.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.reports.balance-sheet') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <input type="date" name="as_of" value="{{ $asOf }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Run</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports.balance-sheet') }}">Reset</a>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:12px; margin-bottom:14px;">
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">Total Assets</div><div style="font-family:var(--mono); font-weight:900; font-size:20px;">TSh {{ number_format((float) $totalAssets, 2) }}</div></div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">Total Liabilities</div><div style="font-family:var(--mono); font-weight:900; font-size:20px;">TSh {{ number_format((float) $totalLiabilities, 2) }}</div></div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">Total Equity</div><div style="font-family:var(--mono); font-weight:900; font-size:20px;">TSh {{ number_format((float) $totalEquity, 2) }}</div></div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:14px;">
        <div>
            <div style="font-weight:800; margin-bottom:8px;">Assets</div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Account</th><th style="width:140px; text-align:right;">Balance</th></tr></thead>
                    <tbody>
                        @foreach($assets as $r)
                            <tr><td><span style="font-family:var(--mono); font-weight:700;">{{ $r['code'] }}</span> — {{ $r['name'] }}</td><td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r['balance'], 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div style="font-weight:800; margin-bottom:8px;">Liabilities</div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Account</th><th style="width:140px; text-align:right;">Balance</th></tr></thead>
                    <tbody>
                        @foreach($liabilities as $r)
                            <tr><td><span style="font-family:var(--mono); font-weight:700;">{{ $r['code'] }}</span> — {{ $r['name'] }}</td><td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r['balance'], 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div style="font-weight:800; margin-bottom:8px;">Equity</div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Account</th><th style="width:140px; text-align:right;">Balance</th></tr></thead>
                    <tbody>
                        @foreach($equity as $r)
                            <tr><td><span style="font-family:var(--mono); font-weight:700;">{{ $r['code'] }}</span> — {{ $r['name'] }}</td><td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r['balance'], 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
