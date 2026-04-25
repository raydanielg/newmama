@extends('layouts.admin')

@section('title', 'Stock Levels')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Stock Levels</h3>
        <p>View and monitor current inventory stock levels.</p>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Items</h3>
        <div class="search-box">
            <input type="text" placeholder="Search items...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>SKU</th>
                    <th>In Stock</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Baby Diapers (Pack of 50)</td>
                    <td>SKU-0001</td>
                    <td>150</td>
                    <td>20</td>
                    <td><span class="badge status-trying">OK</span></td>
                </tr>
                <tr>
                    <td>Maternity Gown</td>
                    <td>SKU-0002</td>
                    <td>5</td>
                    <td>10</td>
                    <td><span class="badge status-pregnant">Low</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
