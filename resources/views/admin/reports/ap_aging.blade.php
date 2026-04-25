@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Open supplier invoices grouped by aging bucket.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.reports.ap-aging') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
        <input type="date" name="as_of" value="{{ $asOf }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Run</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports.ap-aging') }}">Reset</a>
    </form>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="display:grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap:12px;">
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">Current</div><div style="font-family:var(--mono); font-weight:900;">{{ number_format((float) $totals['current'], 2) }}</div></div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">1-30</div><div style="font-family:var(--mono); font-weight:900;">{{ number_format((float) $totals['1_30'], 2) }}</div></div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">31-60</div><div style="font-family:var(--mono); font-weight:900;">{{ number_format((float) $totals['31_60'], 2) }}</div></div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">61-90</div><div style="font-family:var(--mono); font-weight:900;">{{ number_format((float) $totals['61_90'], 2) }}</div></div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px;"><div style="font-size:11px; color:#6b7280;">90+</div><div style="font-family:var(--mono); font-weight:900;">{{ number_format((float) $totals['90_plus'], 2) }}</div></div>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th style="width:160px;">Doc Ref</th>
                    <th style="width:140px;">Posting Date</th>
                    <th style="width:140px;">Due Date</th>
                    <th style="width:120px;">Bucket</th>
                    <th style="width:160px; text-align:right;">Remaining</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td>{{ optional($r['supplier'])->name ?? '—' }}</td>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $r['document_ref'] }}</td>
                        <td>{{ optional($r['posting_date'])->toDateString() }}</td>
                        <td>{{ optional($r['due_date'])->toDateString() }}</td>
                        <td>{{ str_replace('_', '-', $r['bucket']) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r['remaining'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#6b7280; padding:18px;">No open AP found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
