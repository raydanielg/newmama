@extends('layouts.admin')

@section('title', 'Data Import Hub')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Data Import Hub</h3>
        <p>Centralized location for all system data imports and migrations.</p>
    </div>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:20px;">
    <!-- Inventory Import -->
    <div class="content-card" style="padding:20px; display:flex; flex-direction:column; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; border-radius:10px; background:#dcfce7; color:#166534; display:flex; align-items:center; justify-content:center;">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </div>
            <h3 style="margin:0;">Inventory & Products</h3>
        </div>
        <p style="font-size:13px; color:#6b7280; margin:0;">Bulk import products, SKU, barcodes, and initial stock levels.</p>
        <div style="margin-top:auto; padding-top:10px; display:flex; gap:10px;">
            <a href="{{ route('admin.inventory.products.import') }}" class="btn-primary" style="text-decoration:none; font-size:13px;">Open Import</a>
        </div>
    </div>

    <!-- Import Order (Stock In) -->
    <div class="content-card" style="padding:20px; display:flex; flex-direction:column; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; border-radius:10px; background:#dbeafe; color:#1e40af; display:flex; align-items:center; justify-content:center;">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <h3 style="margin:0;">Import Order (Stock In)</h3>
        </div>
        <p style="font-size:13px; color:#6b7280; margin:0;">Import goods received from suppliers and update vendor balances.</p>
        <div style="margin-top:auto; padding-top:10px; display:flex; gap:10px;">
            <a href="{{ route('admin.imports.import-order.create') }}" class="btn-primary" style="text-decoration:none; font-size:13px;">New Import</a>
            <a href="{{ route('admin.imports.import-order.index') }}" class="btn-secondary" style="text-decoration:none; font-size:13px;">History</a>
        </div>
    </div>

    <!-- Future Imports Placeholder -->
    <div class="content-card" style="padding:20px; display:flex; flex-direction:column; gap:12px; opacity:0.6; border-style:dashed;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; border-radius:10px; background:#f3f4f6; color:#6b7280; display:flex; align-items:center; justify-content:center;">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h3 style="margin:0;">Customers & Leads</h3>
        </div>
        <p style="font-size:13px; color:#6b7280; margin:0;">Import customer database, contacts, and historical balances.</p>
        <div style="margin-top:auto; padding-top:10px;">
            <span style="font-size:12px; font-weight:800; color:#9ca3af;">COMING SOON</span>
        </div>
    </div>
</div>
@endsection
