@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Bulk Import Products</h3>
        <p>Upload a CSV file, preview results, then confirm to import.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.inventory.products') }}" class="btn-icon" style="text-decoration:none;">Back</a>
    </div>
</div>

@if($errors->any())
    <div class="content-card" style="padding:12px; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; margin-bottom:14px;">
        <div style="font-weight:900; margin-bottom:6px;">Upload failed:</div>
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card" style="padding:16px;">
    <div style="margin-bottom:12px; font-weight:900;">CSV Columns Supported</div>
    <div style="font-size:13px; color:#6b7280; margin-bottom:14px;">
        name, sku, barcode, category, cost_price (or cost), selling_price (or price), qty_on_hand (or qty/stock), image_url (or image)
    </div>

    <div style="margin-bottom:14px; display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <a href="{{ route('admin.inventory.products.import.template') }}" class="btn-primary" style="text-decoration:none;">Download CSV Template</a>
        <div style="font-size:12px; color:#6b7280;">Fill the template and upload it below.</div>
    </div>

    <form method="POST" action="{{ route('admin.inventory.products.import.preview') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".csv,text/csv" required style="margin-bottom:10px;">
        <div>
            <button class="btn-primary" type="submit">Preview Import</button>
        </div>
    </form>
</div>
@endsection
