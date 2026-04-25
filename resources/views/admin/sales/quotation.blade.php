@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>Create draft quotations and track them before converting to invoices.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.vouchers.sales-invoice.create') }}">New Invoice</a>
    </div>
</div>

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <form method="POST" action="{{ route('admin.sales.quotation.store') }}" style="display:grid; grid-template-columns: 160px 1fr 220px 180px 140px; gap:10px; align-items:end;">
        @csrf
        <div>
            <label class="form-label">Ref</label>
            <input value="{{ $nextRef }}" disabled style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
        </div>
        <div>
            <label class="form-label">Description</label>
            <input name="description" value="{{ old('description') }}" required placeholder="Quotation for customer" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Customer</label>
            <select name="customer_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">— Select customer —</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Date</label>
            <input type="date" name="posting_date" value="{{ old('posting_date', now()->toDateString()) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>
        <div>
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" min="0" name="total_amount" value="{{ old('total_amount', 0) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; text-align:right; font-family:var(--mono); font-weight:800;">
        </div>

        <div style="grid-column: 1 / -1; display:flex; justify-content:flex-end; gap:10px;">
            <button class="btn-primary" type="submit">Save Draft</button>
        </div>
    </form>
</div>

<div class="content-card" style="padding:16px;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px;">Date</th>
                    <th style="width:160px;">Ref</th>
                    <th>Customer</th>
                    <th>Description</th>
                    <th style="width:160px; text-align:right;">Amount</th>
                    <th style="width:110px;">Status</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                    <tr>
                        <td>{{ optional($v->posting_date)->toDateString() }}</td>
                        <td style="font-family:var(--mono); font-weight:700;">{{ $v->ref }}</td>
                        <td>
                            @if($v->customer)
                                <a style="text-decoration:none;" href="{{ route('admin.customers.ledger', $v->customer) }}">{{ $v->customer->name }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $v->description }}</td>
                        <td style="text-align:right; font-family:var(--mono);">TSh {{ number_format((float) $v->total_amount, 2) }}</td>
                        <td>{{ $v->status }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.vouchers.view', $v) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#6b7280; padding:18px;">No quotations yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $vouchers->links() }}</div>
</div>
@endsection
