@extends('layouts.admin')

@section('title', 'POS Receipt')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Order #{{ $order->order_number }}</h3>
        <p>Receipt for {{ $order->mother?->full_name ?? $order->customer?->name ?? 'Walk-in Customer' }}</p>
    </div>
    <div class="header-actions">
        <button onclick="window.print()" class="btn-primary">Print Receipt</button>
        <a href="{{ route('admin.pos') }}" class="btn-icon">New Order</a>
    </div>
</div>

@php
    $siteName = \App\Models\SystemSetting::query()->where('key', 'site.name')->value('value') ?: 'Malkia Konnect';
    $tin = \App\Models\SystemSetting::query()->where('key', 'company.tin')->value('value') ?: 'N/A';
    $vrn = \App\Models\SystemSetting::query()->where('key', 'company.vrn')->value('value') ?: 'N/A';
    $companyPhone = \App\Models\SystemSetting::query()->where('key', 'company.phone')->value('value') ?: 'N/A';
    $companyAddress = \App\Models\SystemSetting::query()->where('key', 'company.address')->value('value') ?: 'N/A';

    $customerName = $order->mother?->full_name ?? $order->customer?->name ?? 'Walk-in Customer';
    $customerPhone = $order->mother?->whatsapp_number ?? $order->customer?->phone ?? null;

    $country = $order->mother?->country?->name;
    $region = $order->mother?->region?->name;
    $district = $order->mother?->district?->name;

    $destination = null;
    if (!empty($order->notes)) {
        $destination = trim($order->notes);
    }

    $subtotal = (float) $order->subtotal;
    $discount = (float) ($order->discount_total ?? 0);
    $tax = (float) ($order->tax_total ?? 0);
    $grand = (float) $order->grand_total;

    $verificationCode = strtoupper(substr(hash('sha256', (string) $order->order_number), 0, 16));
@endphp

<div class="content-card receipt-card">
    <div class="receipt-top">
        <div class="receipt-dup">DUPLICATE</div>
        <div class="receipt-star">*** START OF LEGAL RECEIPT ***</div>

        <div class="receipt-center">
            <div class="receipt-brand">{{ strtoupper($siteName) }}</div>
            <div class="receipt-muted">MOBILE: {{ $companyPhone }}</div>
            <div class="receipt-muted">TIN: {{ $tin }}</div>
            <div class="receipt-muted">VRN NO: {{ $vrn }}</div>
            <div class="receipt-muted">{{ $companyAddress }}</div>
        </div>

        <div class="receipt-line"></div>

        <div class="receipt-meta">
            <div><span class="k">UIN:</span> <span class="v">{{ $order->id }}</span></div>
            <div><span class="k">CASHIER:</span> <span class="v">{{ $order->creator?->name ?? 'SYSTEM' }}</span></div>
            <div><span class="k">DATE:</span> <span class="v">{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
            <div><span class="k">ORDER NO:</span> <span class="v">{{ $order->order_number }}</span></div>
            <div><span class="k">PAYMENT:</span> <span class="v">{{ strtoupper($order->payment_method ?: 'N/A') }}</span></div>
        </div>

        <div class="receipt-line"></div>

        <div class="receipt-meta">
            <div><span class="k">CUSTOMER NAME:</span> <span class="v">{{ $customerName }}</span></div>
            @if($customerPhone)
                <div><span class="k">CUSTOMER MOBILE:</span> <span class="v">{{ $customerPhone }}</span></div>
            @endif
            @if($country || $region || $district)
                <div><span class="k">LOCATION:</span> <span class="v">{{ implode(', ', array_filter([$district, $region, $country])) }}</span></div>
            @endif
            @if($destination)
                <div><span class="k">DESTINATION:</span> <span class="v">{{ $destination }}</span></div>
            @endif
        </div>

        <div class="receipt-line"></div>
    </div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th class="col-desc">DESCRIPTION</th>
                <th class="col-qty">QTY</th>
                <th class="col-price">PRICE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td class="col-desc">{{ $item->name }}</td>
                    <td class="col-qty">{{ rtrim(rtrim(number_format((float)$item->quantity, 2, '.', ''), '0'), '.') }}</td>
                    <td class="col-price">{{ number_format((float)$item->subtotal, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="receipt-line"></div>

    <div class="receipt-totals">
        <div class="row"><span>SUBTOTAL AMOUNT:</span><span>{{ number_format($subtotal, 0) }}</span></div>
        <div class="row"><span>DISCOUNT AMOUNT:</span><span>{{ number_format($discount, 0) }}</span></div>
        <div class="row"><span>TAX AMOUNT:</span><span>{{ number_format($tax, 0) }}</span></div>
        <div class="row grand"><span>TOTAL INCL. OF TAX:</span><span>{{ number_format($grand, 0) }}</span></div>
    </div>

    <div class="receipt-line"></div>

    <div class="receipt-center" style="margin-top: 10px;">
        <div class="receipt-muted">RECEIPT VERIFICATION CODE</div>
        <div class="receipt-code">{{ $verificationCode }}</div>

        <div class="receipt-qr" aria-label="QR code placeholder">
            <div class="qr-box"></div>
        </div>

        <div class="receipt-muted">THANK YOU FOR SHOPPING WITH US</div>
        <div class="receipt-star">*** END OF LEGAL RECEIPT ***</div>
    </div>
</div>

<style>
    .receipt-card {
        max-width: 360px;
        margin: 0 auto;
        padding: 14px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        color: #000;
        background: #fff;
    }
    .receipt-top { font-size: 12px; }
    .receipt-center { text-align: center; }
    .receipt-dup { text-align: center; font-weight: 900; margin-bottom: 6px; }
    .receipt-star { text-align: center; font-weight: 900; margin: 6px 0; }
    .receipt-brand { font-weight: 900; font-size: 18px; letter-spacing: 0.5px; }
    .receipt-muted { font-size: 11px; }
    .receipt-line { border-top: 1px dashed #000; margin: 10px 0; }
    .receipt-meta { display: grid; gap: 4px; }
    .receipt-meta .k { font-weight: 900; }
    .receipt-meta .v { font-weight: 500; }

    .receipt-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .receipt-table thead th { border-bottom: 1px dashed #000; padding: 6px 0; }
    .receipt-table tbody td { padding: 6px 0; vertical-align: top; }
    .col-desc { text-align: left; }
    .col-qty { text-align: center; width: 60px; }
    .col-price { text-align: right; width: 90px; }

    .receipt-totals { font-size: 12px; display: grid; gap: 6px; }
    .receipt-totals .row { display: flex; justify-content: space-between; }
    .receipt-totals .grand { font-weight: 900; }
    .receipt-code { font-weight: 900; font-size: 14px; margin: 4px 0 10px; }
    .receipt-qr { display: flex; justify-content: center; margin: 10px 0; }
    .qr-box { width: 110px; height: 110px; border: 2px solid #000; background: repeating-linear-gradient(45deg, #000 0, #000 2px, #fff 2px, #fff 6px); }

@media print {
    .module-header, .admin-sidebar, .admin-header { display: none !important; }
    .content-card { border: none !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; }
    body { background: #fff !important; }
}
</style>
@endsection
