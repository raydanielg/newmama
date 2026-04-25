@extends('layouts.admin')

@section('title', 'Payments')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Payments</h3>
        <p>Track customer payments and receipts.</p>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Payments</h3>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Payer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PMT-0099</td>
                    <td>Mama Neema</td>
                    <td>TSh 45,000</td>
                    <td>Cash</td>
                    <td>Oct 24, 2023</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
