@extends('layouts.admin')

@section('title', $supplier ? 'Edit Supplier' : 'Add Supplier')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $supplier ? 'Edit Supplier' : 'Add Supplier' }}</h3>
        <p>Vendor details and payment terms.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.suppliers') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ $supplier ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store') }}" style="padding: 16px;">
        @csrf
        @if($supplier)
            @method('PUT')
        @endif

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Supplier Name</label>
                <input name="name" value="{{ old('name', $supplier->name ?? '') }}" placeholder="e.g. Meditech Tanzania Ltd" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Contact Person</label>
                <input name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}" placeholder="e.g. John Mwema" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('contact_person')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Payment Terms</label>
                <select name="payment_terms" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @php($terms = ['COD','NET7','NET14','NET30','NET45','NET60','NET90'])
                    @foreach($terms as $t)
                        <option value="{{ $t }}" {{ old('payment_terms', $supplier->payment_terms ?? 'NET30') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('payment_terms')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Phone</label>
                <input name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" placeholder="+255 7XX XXX XXX" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('phone')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Email</label>
                <input name="email" value="{{ old('email', $supplier->email ?? '') }}" placeholder="supplier@email.com" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('email')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Address</label>
                <input name="address" value="{{ old('address', $supplier->address ?? '') }}" placeholder="P.O. Box 1234, Dar es Salaam" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('address')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        @if($supplier)
            <div class="content-card" style="margin-top: 16px; background:#f9fafb; border:1px solid #eef2f7;">
                <div style="padding: 14px; display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px;">
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Supplier Code</div>
                        <div style="font-weight:700;">{{ $supplier->code }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">AP Balance (TZS)</div>
                        <div style="font-weight:700;">{{ number_format((float) $supplier->balance_tzs, 2) }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">USD Balance</div>
                        <div style="font-weight:700;">{{ number_format((float) $supplier->balance_usd, 2) }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Created</div>
                        <div style="font-weight:700;">{{ $supplier->created_at?->format('M d, Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div style="margin-top: 16px; display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Save</button>
            @if($supplier)
                <a class="btn-icon" href="{{ route('admin.suppliers.ledger', $supplier) }}">View Ledger</a>
            @endif
        </div>
    </form>
</div>
@endsection
