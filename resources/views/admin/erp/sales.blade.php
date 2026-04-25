@extends('layouts.admin')

@section('title', 'Sales & Finance')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3 class="fw-bold">{{ $title }}</h3>
        <p class="text-muted">Monitor revenue, expenses, and transaction history.</p>
    </div>
    <div class="header-actions">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle px-4 py-2 fw-bold shadow-sm" type="button" data-bs-toggle="dropdown">
                + Create Transaction
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2">
                <li><a class="dropdown-item rounded-2 py-2" href="{{ route('admin.vouchers.sales-invoice.create') }}">New Sales Invoice</a></li>
                <li><a class="dropdown-item rounded-2 py-2" href="{{ route('admin.vouchers.cash-receipt.create') }}">Receive Payment</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item rounded-2 py-2" href="{{ route('admin.vouchers.cash-payment.create') }}">Record Expense</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="content-card shadow-sm border-0 p-4 mb-4">
    <form method="GET" action="{{ route('admin.sales') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-bold">From Date</label>
            <input type="date" name="from" value="{{ $from }}" class="form-control border-light bg-light">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-bold">To Date</label>
            <input type="date" name="to" value="{{ $to }}" class="form-control border-light bg-light">
        </div>
        <div class="col-md-4">
            <button class="btn btn-dark w-100 py-2 fw-bold" type="submit">Filter Dashboard</button>
        </div>
    </form>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card border-0 shadow-sm p-4 bg-primary text-white h-100">
            <p class="uppercase small fw-bold opacity-75 mb-1">Total Revenue</p>
            <h2 class="fw-black mb-0">TSh {{ number_format($monthlyRevenue, 0) }}</h2>
            <div class="mt-3 small">
                <span class="bg-white bg-opacity-25 px-2 py-1 rounded">Confirmed Invoices</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-0 shadow-sm p-4 bg-success text-white h-100">
            <p class="uppercase small fw-bold opacity-75 mb-1">Cash Sales</p>
            <h2 class="fw-black mb-0">TSh {{ number_format($cashSales ?? 0, 0) }}</h2>
            <div class="mt-3 small">
                <span class="bg-white bg-opacity-25 px-2 py-1 rounded">Direct Cash Inflow</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-0 shadow-sm p-4 bg-danger text-white h-100">
            <p class="uppercase small fw-bold opacity-75 mb-1">Total Expenses</p>
            <h2 class="fw-black mb-0">TSh {{ number_format($monthlyExpenses, 0) }}</h2>
            <div class="mt-3 small">
                <span class="bg-white bg-opacity-25 px-2 py-1 rounded">Payments Recorded</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-0 shadow-sm p-4 bg-dark text-white h-100">
            <p class="uppercase small fw-bold opacity-75 mb-1">Net Position</p>
            <h2 class="fw-black mb-0">TSh {{ number_format($netProfit, 0) }}</h2>
            <div class="mt-3 small">
                <span class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                    {{ $netProfit >= 0 ? 'Surplus' : 'Deficit' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="content-card shadow-sm border-0">
    <div class="card-header bg-white border-bottom-0 p-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold m-0">Recent Transactions</h5>
        <a href="{{ route('admin.sales.register') }}" class="text-primary text-decoration-none small fw-bold">View Sales Register →</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 border-0 text-muted small uppercase fw-bold">Reference</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Type</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Description</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold">Date</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end">Amount</th>
                    <th class="py-3 border-0 text-muted small uppercase fw-bold text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                @forelse($recent as $v)
                <tr>
                    <td class="ps-4"><span class="ref-cell">{{ $v->ref }}</span></td>
                    <td>
                        <span class="badge rounded-pill 
                            @if($v->type == 'sales_invoice') bg-blue-soft text-blue 
                            @elseif($v->type == 'cash_receipt') bg-success-soft text-success 
                            @else bg-danger-soft text-danger @endif">
                            {{ str_replace('_', ' ', strtoupper($v->type)) }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ Str::limit($v->description, 40) }}</td>
                    <td>{{ $v->posting_date?->format('M d, Y') }}</td>
                    <td class="text-end fw-black">TSh {{ number_format((float) $v->total_amount, 0) }}</td>
                    <td class="text-end pe-4">
                        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.vouchers.view', $v) }}">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted italic">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
