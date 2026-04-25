@extends('layouts.admin')

@section('title', 'Suppliers')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Suppliers</h3>
        <p>Manage suppliers and contacts.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Add Supplier
        </button>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Suppliers</h3>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ABC Supplies</td>
                    <td>+255 700 000 000</td>
                    <td>abc@example.com</td>
                    <td><button class="btn-icon">Edit</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
