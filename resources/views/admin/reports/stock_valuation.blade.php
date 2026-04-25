@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Inventory value based on qty on hand × cost price.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.reports') }}">Back</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="padding:12px; border:1px solid #e5e7eb; border-radius:12px; background:#f9fafb;">
        <div style="font-size:11px; color:#6b7280;">Total Stock Value</div>
        <div style="font-family:var(--mono); font-weight:900; font-size:24px;">TSh {{ number_format((float) $total, 2) }}</div>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px;">SKU</th>
                    <th>Product</th>
                    <th style="width:140px; text-align:right;">Qty</th>
                    <th style="width:140px; text-align:right;">Cost</th>
                    <th style="width:160px; text-align:right;">Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $r['sku'] }}</td>
                        <td>{{ $r['name'] }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r['qty'], 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono);">{{ number_format((float) $r['cost'], 2) }}</td>
                        <td style="text-align:right; font-family:var(--mono); font-weight:800;">{{ number_format((float) $r['value'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#6b7280; padding:18px;">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
