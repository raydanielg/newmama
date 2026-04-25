@extends('layouts.admin')

@section('title', 'Vouchers')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Vouchers</h3>
        <p>Post accounting vouchers.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Cash Payment</h3>
            <p class="stat-label">Record a cash expense or supplier payment</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.cash-payment.create') }}">New Cash Payment</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Purchase Invoice</h3>
            <p class="stat-label">Dr GRN Interim (1121) / Cr AP (2010) · Creates open vendor entry</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.purchase-invoice.create') }}">New Purchase Invoice</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Cash Receipt</h3>
            <p class="stat-label">Dr Cash/Bank / Cr Revenue or AR (1200) · Optional customer ledger update</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.cash-receipt.create') }}">New Cash Receipt</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Bank Transfer</h3>
            <p class="stat-label">Dr Target Bank/Cash / Cr Source Bank/Cash · Moves funds between your accounts</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.bank-transfer.create') }}">New Bank Transfer</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Purchase Return</h3>
            <p class="stat-label">Dr AP (2010) / Cr Inventory (1110) · Stock reduced · Supplier balance reduced</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.purchase-return.create') }}">New Purchase Return</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Contra Entry</h3>
            <p class="stat-label">Dr Destination Cash/Bank / Cr Source Cash/Bank · No P&L impact</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.contra-entry.create') }}">New Contra Entry</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Credit Note</h3>
            <p class="stat-label">Dr Revenue (4010) / Cr AR (1050) · Customer owes less</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.credit-note.create') }}">New Credit Note</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">Sales Invoice</h3>
            <p class="stat-label">Dr AR (1050) / Cr Revenue (4011) + VAT (2020) · Posts COGS and reduces inventory</p>
        </div>
        <div style="margin-top:10px;">
            <a class="btn-primary" style="text-decoration:none;" href="{{ route('admin.vouchers.sales-invoice.create') }}">New Sales Invoice</a>
        </div>
    </div>
</div>
@endsection
