@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Import Order (Stock In)</h3>
        <p>Upload CSV file, preview rows, then confirm to post stock and supplier balance.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.imports.import-order.index') }}" class="btn-icon" style="text-decoration:none;">Back</a>
    </div>
</div>

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

<div class="content-card" style="padding:16px; margin-bottom:14px;">
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <a href="{{ route('admin.imports.import-order.template') }}" class="btn-primary" style="text-decoration:none;">Download CSV Template</a>
        <div style="font-size:12px; color:#6b7280;">Fill the template and upload below.</div>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <form method="POST" action="{{ route('admin.imports.import-order.preview') }}" enctype="multipart/form-data">
        @csrf

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:14px;">
            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Reference *</label>
                <input name="ref" value="{{ old('ref', $nextRef) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Posting Date *</label>
                <input type="date" name="posting_date" value="{{ old('posting_date', $defaultDate) }}" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>

            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Supplier (Optional)</label>
                <select name="supplier_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">— Select Supplier —</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ (string) old('supplier_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                <div style="font-size:12px; color:#6b7280; margin-top:6px;">If supplier is not in list, type Supplier Name below.</div>
            </div>
            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Supplier Name (Optional)</label>
                <input name="supplier_name" value="{{ old('supplier_name') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>

            <div>
                <label style="display:block; font-weight:900; margin-bottom:6px;">Import Type *</label>
                <select name="import_type" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="product" {{ old('import_type') === 'product' ? 'selected' : '' }}>New Products / Restock</option>
                    <option value="opening_stock" {{ old('import_type') === 'opening_stock' ? 'selected' : '' }}>Opening Stock</option>
                    <option value="adjustment" {{ old('import_type') === 'adjustment' ? 'selected' : '' }}>Inventory Adjustment</option>
                </select>
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:900; margin-bottom:6px;">Notes</label>
                <input name="notes" value="{{ old('notes') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:900; margin-bottom:6px;">CSV File *</label>
                <input type="file" name="file" accept=".csv,text/csv" required>
                <div style="font-size:12px; color:#6b7280; margin-top:6px;">Required columns: product_name, qty, unit_cost. Others optional.</div>
            </div>
        </div>

        <div style="margin-top:18px; display:flex; gap:10px;">
            <button class="btn-primary" type="submit">Preview</button>
        </div>
    </form>
</div>
@endsection
