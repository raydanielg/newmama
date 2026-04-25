@extends('layouts.admin')

@section('title', 'Journal ' . $journal->ref)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Journal {{ $journal->ref }}</h3>
        <p>{{ $journal->description }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.journals') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <div style="display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:12px; margin-bottom:14px;">
        <div>
            <div style="font-size:12px; color:#6b7280;">Posting Date</div>
            <div style="font-weight:700;">{{ optional($journal->posting_date)->toDateString() }}</div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Type</div>
            <div style="font-weight:700;">{{ $journal->journal_type }}</div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Source</div>
            <div style="font-weight:700;">{{ $journal->source_type }} · {{ $journal->source_ref }}</div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Status</div>
            <div style="font-weight:700;">{{ $journal->status }}</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:120px;">Account</th>
                    <th>Description</th>
                    <th style="width:160px; text-align:right;">Debit</th>
                    <th style="width:160px; text-align:right;">Credit</th>
                </tr>
            </thead>
            <tbody>
                @php($td=0)
                @php($tc=0)
                @foreach($journal->lines as $l)
                    @php($td += (float) $l->debit)
                    @php($tc += (float) $l->credit)
                    <tr>
                        <td>{{ $l->line_number }}</td>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $l->account?->code }}</td>
                        <td>{{ $l->description }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->debit, 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->credit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right;">Totals</th>
                    <th style="text-align:right; font-family:var(--mono);">{{ number_format((float) $td, 2) }}</th>
                    <th style="text-align:right; font-family:var(--mono);">{{ number_format((float) $tc, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
