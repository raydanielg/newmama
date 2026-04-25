@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Inventory Dashboard</h3>
        <p>Manage your stock levels, products, and suppliers.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Add New Product
        </button>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">1,240</h3>
            <p class="stat-label">Total Products</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value text-danger">12</h3>
            <p class="stat-label">Low Stock Items</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-details">
            <h3 class="stat-value">45</h3>
            <p class="stat-label">Suppliers</p>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Product Inventory</h3>
        <div class="search-box">
            <input type="text" placeholder="Search products...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Baby Diapers (Pack of 50)</td>
                    <td>Supplies</td>
                    <td>TSh 25,000</td>
                    <td>150</td>
                    <td><span class="badge status-trying">In Stock</span></td>
                    <td><button class="btn-icon">Edit</button></td>
                </tr>
                <tr>
                    <td>Maternity Gown</td>
                    <td>Clothing</td>
                    <td>TSh 35,000</td>
                    <td>5</td>
                    <td><span class="badge status-pregnant">Low Stock</span></td>
                    <td><button class="btn-icon">Edit</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
