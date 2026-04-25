@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Purchases and returns for selected period.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.reports.purchase-register') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <input name="q" value="{{ request('q') }}" placeholder="Search ref/description" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="from" value="{{ $from }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="to" value="{{ $to }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Run</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports.purchase-register') }}">Reset</a>
    </form>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="font-size:11px; color:#6b7280;">Total (period)</div>
    <div style="font-family:var(--mono); font-weight:900; font-size:22px;">TSh {{ number_format((float) $total, 2) }}</div>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px;">Date</th>
                    <th style="width:160px;">Reference</th>
                    <th style="width:140px;">Type</th>
                    <th>Description</th>
                    <th style="width:160px; text-align:right;">Amount</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                    <tr>
                        <td>{{ optional($v->posting_date)->toDateString() }}</td>
                        <td><span class="ref-cell">{{ $v->ref }}</span></td>
                        <td>{{ $v->type }}</td>
                        <td>{{ $v->description }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $v->total_amount, 2) }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.vouchers.view', $v) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; color:#6b7280; padding:18px;">No purchases found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $vouchers->links() }}</div>
</div>
@endsection
