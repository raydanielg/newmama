@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $order->ref }}</h3>
        <p>Import Order details and stock-in lines.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.imports.import-order.index') }}" class="btn-icon" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:10px; font-size:13px;">
        <div><strong>Posting Date:</strong> {{ $order->posting_date?->format('M d, Y') }}</div>
        <div><strong>Supplier:</strong> {{ $order->supplier?->name ?: ($order->supplier_name ?: '—') }}</div>
        <div><strong>Total Lines:</strong> {{ $order->total_lines }}</div>
        <div><strong>Total Cost:</strong> TSh {{ number_format((float)$order->total_cost, 0) }}</div>
        <div><strong>Created By:</strong> {{ $order->creator?->name ?: '—' }}</div>
        <div><strong>Source File:</strong> {{ $order->source_file_name ?: '—' }}</div>
        <div style="grid-column: span 2;"><strong>Notes:</strong> {{ $order->notes ?: '—' }}</div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="width:140px;">SKU</th>
                    <th style="width:160px;">Barcode</th>
                    <th style="width:90px;">Qty</th>
                    <th style="width:110px;">Unit Cost</th>
                    <th style="width:120px;">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->lines as $l)
                    <tr>
                        <td style="font-weight:900;">{{ $l->product_name }}</td>
                        <td>{{ $l->sku ?: '—' }}</td>
                        <td style="font-family:var(--mono);">{{ $l->barcode ?: '—' }}</td>
                        <td>{{ rtrim(rtrim(number_format((float)$l->qty, 2, '.', ''), '0'), '.') }}</td>
                        <td>{{ number_format((float)$l->unit_cost, 0) }}</td>
                        <td>{{ number_format((float)$l->line_total, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
