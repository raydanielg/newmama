@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Filter transactions and view totals.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors') }}">Overview</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.hub') }}">Hub</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.portfolio') }}">Portfolio</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.investors.reports') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
        <div>
            <label class="form-label">From</label>
            <input type="date" name="from" value="{{ $from }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">To</label>
            <input type="date" name="to" value="{{ $to }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Type</label>
            <select name="type" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All</option>
                @foreach(['contribution','withdrawal','dividend','adjustment'] as $t)
                    <option value="{{ $t }}" {{ $type===$t?'selected':'' }}>{{ strtoupper($t) }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:240px;">
            <label class="form-label">Investor</label>
            <select name="investor_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All</option>
                @foreach($investors as $i)
                    <option value="{{ $i->id }}" {{ (string) $investorId === (string) $i->id ? 'selected' : '' }}>{{ $i->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.reports') }}">Reset</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.reports.csv', request()->query()) }}">Export CSV</a>
    </form>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $summary['count'] }}</h3><p class="stat-label">Transactions</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $summary['total'], 2) }}</h3><p class="stat-label">Total Amount</p></div></div>
</div>

<div class="content-card" style="padding:16px;">
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
                @forelse($rows as $r)
                    <tr>
                        <td style="font-family:var(--mono);">{{ optional($r->posting_date)->toDateString() }}</td>
                        <td>{{ optional($r->investor)->name ?: '—' }}</td>
                        <td>{{ strtoupper($r->type) }}</td>
                        <td>{{ $r->description ?: ($r->reference ?: '—') }}</td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $r->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No rows.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $rows->links() }}</div>
</div>
@endsection
