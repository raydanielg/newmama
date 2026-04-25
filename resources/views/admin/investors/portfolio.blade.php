@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Balances by investor and quick access to profiles.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors') }}">Overview</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.hub') }}">Hub</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.reports') }}">Reports</a>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:14px;">
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">TSh {{ number_format((float) $total, 2) }}</h3><p class="stat-label">Total Portfolio Balance</p></div></div>
    <div class="stat-card"><div class="stat-details"><h3 class="stat-value">{{ (int) $investors->total() }}</h3><p class="stat-label">Investors</p></div></div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.investors.portfolio') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <input name="q" value="{{ $q }}" placeholder="Search investor" style="flex:1; min-width:260px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Search</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.portfolio') }}">Reset</a>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Investor</th>
                    <th style="width:140px;">Number</th>
                    <th style="width:160px;">Phone</th>
                    <th style="width:180px; text-align:right;">Balance</th>
                    <th style="width:140px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($investors as $i)
                    <tr>
                        <td>{{ $i->name }}</td>
                        <td style="font-family:var(--mono); font-weight:800;">{{ $i->investor_number }}</td>
                        <td>{{ $i->phone ?: '—' }}</td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:900;">{{ number_format((float) $i->balance, 2) }}</td>
                        <td style="text-align:right;"><a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.show', $i) }}">Profile</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No investors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $investors->links() }}</div>
</div>
@endsection
