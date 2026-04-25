@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>View and print posted invoices.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.vouchers.sales-invoice.create') }}" class="btn-primary" style="text-decoration:none;">New Invoice</a>
    </div>
</div>

<div class="content-card" style="padding: 16px;">
    <form method="GET" action="{{ route('admin.sales.invoices') }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px;">
        <input name="q" value="{{ request('q') }}" placeholder="Search ref/description" style="flex:1; min-width:220px; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="from" value="{{ request('from') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <input type="date" name="to" value="{{ request('to') }}" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        <button class="btn-primary" type="submit">Filter</button>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.sales.invoices') }}">Reset</a>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px;">Date</th>
                    <th style="width:160px;">Reference</th>
                    <th>Description</th>
                    <th style="width:140px; text-align:right;">Net</th>
                    <th style="width:140px; text-align:right;">VAT</th>
                    <th style="width:160px; text-align:right;">Total</th>
                    <th style="width:90px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $v)
                    <tr>
                        <td>{{ optional($v->posting_date)->toDateString() }}</td>
                        <td><span class="ref-cell">{{ $v->ref }}</span></td>
                        <td>{{ $v->description }}</td>
                        <td style="text-align:right; font-family: var(--mono);">{{ number_format((float) $v->subtotal, 2) }}</td>
                        <td style="text-align:right; font-family: var(--mono);">{{ number_format((float) $v->vat_amount, 2) }}</td>
                        <td style="text-align:right; font-family: var(--mono); font-weight:800;">{{ number_format((float) $v->total_amount, 2) }}</td>
                        <td style="text-align:right;">
                            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.vouchers.view', $v) }}">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $vouchers->links() }}</div>
</div>
@endsection
