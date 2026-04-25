@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Import Preview</h3>
        <p>Review rows, then confirm to post stock in and supplier balance.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.imports.import-order.create') }}" class="btn-icon" style="text-decoration:none;">Upload Again</a>
        <a href="{{ route('admin.imports.import-order.index') }}" class="btn-icon" style="text-decoration:none;">Back to History</a>
    </div>
</div>

<div class="content-card" style="padding:14px; margin-bottom:14px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
    <div style="font-weight:900;">
        Ref: <span style="font-family:var(--mono);">{{ $meta['ref'] ?? '—' }}</span>
        | Type: <span style="text-transform:capitalize; color:var(--malkia-red);">{{ str_replace('_', ' ', $meta['import_type'] ?? 'product') }}</span>
        | Valid: <span style="color:#166534;">{{ $validCount }}</span>
        | Invalid: <span style="color:#991b1b;">{{ $invalidCount }}</span>
        | Total Cost: <span style="color:#111827;">TSh {{ number_format((float)$totalCost, 0) }}</span>
    </div>
    <form method="POST" action="{{ route('admin.imports.import-order.confirm') }}">
        @csrf
        <button class="btn-primary" type="submit" {{ $validCount === 0 ? 'disabled' : '' }}>Confirm & Post ({{ $validCount }})</button>
    </form>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:80px;">Line</th>
                    <th>Product</th>
                    <th style="width:140px;">SKU</th>
                    <th style="width:160px;">Barcode</th>
                    <th style="width:90px;">Qty</th>
                    <th style="width:110px;">Unit Cost</th>
                    <th style="width:120px;">Line Total</th>
                    <th style="width:110px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    @php($p = $r['payload'])
                    <tr style="{{ empty($r['errors']) ? '' : 'background:#fef2f2;' }}">
                        <td style="font-family:var(--mono); font-weight:900;">{{ $r['line'] }}</td>
                        <td style="font-weight:900;">{{ $p['product_name'] }}</td>
                        <td>{{ $p['sku'] }}</td>
                        <td style="font-family:var(--mono);">{{ $p['barcode'] }}</td>
                        <td>{{ rtrim(rtrim(number_format((float)$p['qty'], 2, '.', ''), '0'), '.') }}</td>
                        <td>{{ number_format((float)$p['unit_cost'], 0) }}</td>
                        <td>{{ number_format((float)$p['line_total'], 0) }}</td>
                        <td>
                            @if(empty($r['errors']))
                                <span class="badge" style="background:#dcfce7; color:#166534; font-weight:900;">OK</span>
                            @else
                                <span class="badge" style="background:#fee2e2; color:#991b1b; font-weight:900;">ERROR</span>
                                <div style="margin-top:6px; font-size:12px; color:#991b1b;">
                                    {{ implode('; ', $r['errors']) }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
