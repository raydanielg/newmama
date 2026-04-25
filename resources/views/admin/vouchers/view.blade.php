@extends('layouts.admin')

@section('title', 'View Voucher ' . $voucher->ref)

@section('admin-content')
<div class="module-header" style="align-items:flex-start;">
    <div class="header-info">
        <h3>{{ $voucher->type }} · <span class="ref-cell">{{ $voucher->ref }}</span></h3>
        <p>{{ $voucher->description }}</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ url()->previous() }}" class="btn-icon" style="text-decoration:none;">Back</a>
        <button type="button" class="btn-primary" onclick="window.print()">Print</button>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <div style="font-weight:800; font-size:16px;">Malkia Konnect</div>
            <div style="color:#6b7280; font-size:12px;">Receipt / Voucher Preview</div>
        </div>
        <div style="text-align:right;">
            <div class="ref-pill" style="justify-content:flex-end;">
                Reference:
                <span class="ref-cell">{{ $voucher->ref }}</span>
            </div>
            <div style="font-size:12px; color:#6b7280;">Date: {{ optional($voucher->posting_date)->toDateString() }}</div>
            <div style="font-size:12px; color:#6b7280;">Status: {{ $voucher->status }}</div>
        </div>
    </div>

    <hr style="margin:14px 0; border:none; border-top:1px solid #e5e7eb;">

    <div style="display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:14px;">
        <div>
            <div style="font-size:12px; color:#6b7280;">Customer</div>
            <div style="font-weight:700;">
                {{ $voucher->customer?->name ?? '—' }}
                @if($voucher->customer)
                    <a href="{{ route('admin.customers.ledger', $voucher->customer) }}" class="btn-icon" style="margin-left:8px; text-decoration:none;">View</a>
                @endif
            </div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Supplier</div>
            <div style="font-weight:700;">
                {{ $voucher->supplier?->name ?? '—' }}
                @if($voucher->supplier)
                    <a href="{{ route('admin.suppliers.ledger', $voucher->supplier) }}" class="btn-icon" style="margin-left:8px; text-decoration:none;">View</a>
                @endif
            </div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Payment Method</div>
            <div style="font-weight:700;">{{ $voucher->payment_method ?? '—' }}</div>
        </div>
        <div>
            <div style="font-size:12px; color:#6b7280;">Notes</div>
            <div style="font-weight:700;">{{ $voucher->notes ?: '—' }}</div>
        </div>
    </div>

    @if($voucher->lines->count() > 0)
        <hr style="margin:14px 0; border:none; border-top:1px solid #e5e7eb;">

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Description</th>
                        <th style="width:110px; text-align:right;">Qty</th>
                        <th style="width:130px; text-align:right;">Unit</th>
                        <th style="width:150px; text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voucher->lines as $l)
                        <tr>
                            <td>{{ $l->line_number }}</td>
                            <td>{{ $l->description }}</td>
                            <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->qty, 2) }}</td>
                            <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) ($l->unit_price ?? $l->unit_cost), 2) }}</td>
                            <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $l->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div style="display:flex; justify-content:flex-end; margin-top:14px;">
        <div style="min-width: 320px; background:#f9fafb; border:1px solid #eef2f7; border-radius:10px; padding: 12px 14px;">
            @if(!is_null($voucher->subtotal))
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:#6b7280;">Net</span>
                    <span style="font-family:var(--mono);">{{ number_format((float) $voucher->subtotal, 2) }}</span>
                </div>
            @endif
            @if(!is_null($voucher->vat_amount))
                <div style="display:flex; justify-content:space-between; margin-top:6px;">
                    <span style="color:#6b7280;">VAT</span>
                    <span style="font-family:var(--mono);">{{ number_format((float) $voucher->vat_amount, 2) }}</span>
                </div>
            @endif
            <div style="display:flex; justify-content:space-between; margin-top:6px; font-weight:900;">
                <span>Total</span>
                <span style="font-family:var(--mono);">{{ number_format((float) $voucher->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-top:14px;">
    <div style="font-weight:800; margin-bottom:8px;">Journal</div>
    @if($voucher->journal)
        <div style="display:flex; justify-content:space-between; margin-bottom:10px; color:#6b7280;">
            <div>{{ $voucher->journal->ref }} · {{ $voucher->journal->journal_type }}</div>
            <div>{{ optional($voucher->journal->posting_date)->toDateString() }}</div>
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
                    @foreach($voucher->journal->lines as $l)
                        <tr>
                            <td>{{ $l->line_number }}</td>
                            <td style="font-family:var(--mono); font-weight:700;">{{ $l->account?->code }}</td>
                            <td>{{ $l->description }}</td>
                            <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->debit, 2) }}</td>
                            <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $l->credit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="color:#6b7280;">No journal linked.</div>
    @endif
</div>

<style>
@media print {
    .admin-wrapper .admin-sidebar,
    .admin-wrapper .admin-header,
    .module-header .header-actions {
        display: none !important;
    }
    .admin-main { margin: 0 !important; }
    .content-card { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
}
</style>
@endsection
