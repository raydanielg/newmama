@extends('layouts.admin')

@section('title', 'Konnect Hub')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Konnect Hub</h3>
        <p>Centralized monitoring and management for Malkia Konnect operations.</p>
    </div>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:20px; margin-bottom:24px;">
    <!-- Statistics Cards -->
    <div class="content-card" style="padding:20px; border-left:4px solid #3b82f6;">
        <div style="font-size:12px; color:#6b7280; font-weight:800; text-transform:uppercase;">Active Employees</div>
        <div style="font-size:28px; font-weight:900; margin-top:8px;">{{ \App\Models\Employee::where('employment_status', 'active')->count() }}</div>
    </div>
    
    <div class="content-card" style="padding:20px; border-left:4px solid #10b981;">
        <div style="font-size:12px; color:#6b7280; font-weight:800; text-transform:uppercase;">Total Products</div>
        <div style="font-size:28px; font-weight:900; margin-top:8px;">{{ \App\Models\Product::count() }}</div>
    </div>

    <div class="content-card" style="padding:20px; border-left:4px solid #f59e0b;">
        <div style="font-size:12px; color:#6b7280; font-weight:800; text-transform:uppercase;">Pending Leaves</div>
        <div style="font-size:28px; font-weight:900; margin-top:8px;">{{ \App\Models\HrmLeaveRequest::where('status', 'pending')->count() }}</div>
    </div>

    <div class="content-card" style="padding:20px; border-left:4px solid #ef4444;">
        <div style="font-size:12px; color:#6b7280; font-weight:800; text-transform:uppercase;">Asset Alerts</div>
        <div style="font-size:28px; font-weight:900; margin-top:8px;">{{ \App\Models\HrmAsset::where('status', 'maintenance')->count() }}</div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
    <!-- Recent Activity -->
    <div class="content-card">
        <div class="card-header" style="padding:16px; border-bottom:1px solid #e5e7eb;">
            <h3 style="margin:0;">Recent Import Orders</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Ref</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\ImportOrder::with('supplier')->latest()->limit(5)->get() as $io)
                    <tr>
                        <td style="font-family:var(--mono); font-weight:900;">{{ $io->ref }}</td>
                        <td>{{ $io->supplier?->name ?: $io->supplier_name }}</td>
                        <td>{{ $io->posting_date->format('M d, Y') }}</td>
                        <td>{{ number_format($io->total_cost, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px; border-top:1px solid #e5e7eb; text-align:center;">
            <a href="{{ route('admin.imports.import-order.index') }}" style="color:#3b82f6; font-weight:800; text-decoration:none; font-size:13px;">View All Imports →</a>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="content-card">
        <div class="card-header" style="padding:16px; border-bottom:1px solid #e5e7eb;">
            <h3 style="margin:0;">Quick Actions</h3>
        </div>
        <div style="padding:16px; display:flex; flex-direction:column; gap:10px;">
            <a href="{{ route('admin.inventory.products.create') }}" class="btn-secondary" style="text-decoration:none; text-align:center;">Add New Product</a>
            <a href="{{ route('admin.imports.import-order.create') }}" class="btn-secondary" style="text-decoration:none; text-align:center;">Stock In (Import)</a>
            <a href="{{ route('admin.hrm.leave') }}" class="btn-secondary" style="text-decoration:none; text-align:center;">Manage Leaves</a>
            <a href="{{ route('admin.hrm.assets') }}" class="btn-secondary" style="text-decoration:none; text-align:center;">Asset Register</a>
        </div>
    </div>
</div>
@endsection
