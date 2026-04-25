@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Import Preview</h3>
        <p>Review rows before confirming import. Missing SKU/Barcode will be auto-generated.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.inventory.products.import') }}" class="btn-icon" style="text-decoration:none;">Upload Again</a>
        <a href="{{ route('admin.inventory.products') }}" class="btn-icon" style="text-decoration:none;">Back to Products</a>
    </div>
</div>

<div class="content-card" style="padding:14px; margin-bottom:14px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
    <div style="font-weight:900;">Valid: <span style="color:#166534;">{{ $validCount }}</span> | Invalid: <span style="color:#991b1b;">{{ $invalidCount }}</span></div>
    <form method="POST" action="{{ route('admin.inventory.products.import.confirm') }}">
        @csrf
        <button class="btn-primary" type="submit" {{ $validCount === 0 ? 'disabled' : '' }}>Confirm Import ({{ $validCount }})</button>
    </form>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:80px;">Line</th>
                    <th>Name</th>
                    <th style="width:140px;">SKU</th>
                    <th style="width:160px;">Barcode</th>
                    <th style="width:110px;">Cost</th>
                    <th style="width:110px;">Selling</th>
                    <th style="width:90px;">Stock</th>
                    <th style="width:110px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    @php($p = $r['payload'])
                    <tr style="{{ empty($r['errors']) ? '' : 'background:#fef2f2;' }}">
                        <td style="font-family:var(--mono); font-weight:900;">{{ $r['line'] }}</td>
                        <td style="font-weight:900;">{{ $p['name'] }}</td>
                        <td>{{ $p['sku'] }}</td>
                        <td style="font-family:var(--mono);">{{ $p['barcode'] }}</td>
                        <td>{{ number_format((float)$p['cost_price'], 0) }}</td>
                        <td>{{ number_format((float)$p['selling_price'], 0) }}</td>
                        <td>{{ (int)$p['qty_on_hand'] }}</td>
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
