@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Cash & bank balances with account ledger view.</p>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <form method="GET" action="{{ route('admin.banks') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <select name="code" style="min-width:240px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            @foreach($accounts as $a)
                <option value="{{ $a->code }}" {{ optional($selectedAccount)->code == $a->code ? 'selected' : '' }}>
                    {{ $a->code }} — {{ $a->name }}
                </option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ $from }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="to" value="{{ $to }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Load</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.banks') }}">Reset</a>
    </form>

    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:12px; margin-bottom:14px;">
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px; background:#fff;">
            <div style="font-size:11px; color:#6b7280;">Selected Account Balance</div>
            <div style="font-family:var(--mono); font-weight:800; font-size:22px;">{{ number_format((float) optional($selectedAccount)->balance, 2) }}</div>
        </div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px; background:#fff;">
            <div style="font-size:11px; color:#6b7280;">In ({{ $from }} → {{ $to }})</div>
            <div style="font-family:var(--mono); font-weight:800; font-size:22px; color:#16a34a;">{{ number_format((float) $summary['in'], 2) }}</div>
        </div>
        <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px; background:#fff;">
            <div style="font-size:11px; color:#6b7280;">Out ({{ $from }} → {{ $to }})</div>
            <div style="font-family:var(--mono); font-weight:800; font-size:22px; color:#dc2626;">{{ number_format((float) $summary['out'], 2) }}</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px;">Posting Date</th>
                    <th style="width:160px;">Journal Ref</th>
                    <th>Description</th>
                    <th style="width:140px; text-align:right;">Debit</th>
                    <th style="width:140px; text-align:right;">Credit</th>
                    <th style="width:160px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($lines as $l)
                    <tr>
                        <td>{{ optional($l->journal->posting_date)->toDateString() }}</td>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $l->journal->ref ?? '—' }}</td>
                        <td>{{ $l->description }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->debit, 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->credit, 2) }}</td>
                        <td style="text-align:right;">
                            @if($l->journal)
                                <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.journals.show', $l->journal) }}">View</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#6b7280; padding:18px;">No ledger entries for selected period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
