@extends('layouts.admin')

@section('title', 'Products')

@section('admin-content')
<style>
    .btn-ico-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-ico-circle:hover { background: #e5e7eb; color: #111827; }
    .btn-ico-circle.edit:hover { background: #dbeafe; color: #2563eb; border-color: #bfdbfe; }
    .btn-ico-circle.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fecaca; }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(17,24,39,0.55);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 9999;
    }
    .modal {
        width: 100%;
        max-width: 820px;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(17,24,39,0.1);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    .modal-header {
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    .modal-title { font-size: 16px; font-weight: 900; }
    .modal-body { padding: 16px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .field label { display:block; font-size: 12px; font-weight: 800; color:#6b7280; margin-bottom: 6px; }
    .field input, .field select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
    }
    .modal-footer {
        padding: 16px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #e5e7eb;
        background: #fff;
    }
    .btn-secondary {
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        font-weight: 800;
        cursor: pointer;
    }
    .thumb {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f3f4f6;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        border: 1px solid #e5e7eb;
    }
</style>

<div class="module-header">
    <div class="header-info">
        <h3>Products</h3>
        <p>Manage your product catalog (stock, barcode, images).</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.inventory.products.import') }}" class="btn-icon" style="text-decoration:none;">Bulk Upload</a>
        <a href="{{ route('admin.inventory.products.create') }}" class="btn-primary" style="text-decoration:none;">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Add Product
        </a>
    </div>
</div>

@if(session('status'))
    <div class="content-card" style="padding:12px; border:1px solid #bbf7d0; background:#f0fdf4; color:#166534; margin-bottom:14px;">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="content-card" style="padding:12px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; margin-bottom:14px;">
        <div style="font-weight:900; margin-bottom:6px;">Please fix the errors below:</div>
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card">
    <div class="card-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px;">
        <h3 style="margin:0;">Product List</h3>
        <form method="GET" action="{{ route('admin.inventory.products') }}" style="display:flex; gap:10px; align-items:center;">
            <input name="q" value="{{ request('q') }}" type="text" placeholder="Search name, sku, barcode..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:260px;">
            <button class="btn-primary" type="submit">Search</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">Image</th>
                    <th>Name</th>
                    <th style="width:110px;">SKU</th>
                    <th style="width:140px;">Barcode</th>
                    <th style="width:120px;">Category</th>
                    <th style="width:120px;">Selling</th>
                    <th style="width:90px;">Stock</th>
                    <th style="width:110px;">Stock Status</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td>
                        <span class="thumb">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            @endif
                        </span>
                    </td>
                    <td style="font-weight:800;">{{ $p->name }}</td>
                    <td>{{ $p->sku }}</td>
                    <td style="font-family: var(--mono);">{{ $p->barcode ?: '—' }}</td>
                    <td>{{ $p->category ?: '—' }}</td>
                    <td>TSh {{ number_format($p->selling_price, 0) }}</td>
                    <td>{{ (int) $p->qty_on_hand }}</td>
                    <td>
                        @php($qty = (float) $p->qty_on_hand)
                        @if($qty <= 0)
                            <span class="badge" style="background:#fee2e2; color:#991b1b; font-weight:900;">OUT</span>
                        @elseif($qty <= 5)
                            <span class="badge" style="background:#fef3c7; color:#92400e; font-weight:900;">LOW</span>
                        @else
                            <span class="badge" style="background:#dcfce7; color:#166534; font-weight:900;">IN STOCK</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $p->is_active ? 'status-trying' : 'status-pregnant' }}">{{ $p->is_active ? 'ACTIVE' : 'INACTIVE' }}</span>
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <a class="btn-ico-circle edit" title="Edit" href="{{ route('admin.inventory.products.edit', $p) }}">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.inventory.products.destroy', $p) }}" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-ico-circle delete" title="Delete">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center; padding:20px; color:#6b7280;">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px; padding: 0 14px 14px;">
        {{ $products->links() }}
    </div>
</div>
@endsection
