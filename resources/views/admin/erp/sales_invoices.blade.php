@extends('layouts.admin')

@section('title', 'Invoices')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Invoices</h3>
        <p>Create and manage customer invoices.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Create Invoice
        </button>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Invoice List</h3>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INV-00124</td>
                    <td>Mama Neema</td>
                    <td>TSh 45,000</td>
                    <td><span class="badge status-trying">Paid</span></td>
                    <td>Oct 24, 2023</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
