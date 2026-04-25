@extends('layouts.admin')

@section('title', $customer ? 'Edit Customer' : 'Add Customer')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $customer ? 'Edit Customer' : 'Add Customer' }}</h3>
        <p>{{ $type === 'debtor' ? 'Debtor account details' : 'Cash customer contact details' }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.customers', ['type' => $type]) }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ $customer ? route('admin.customers.update', $customer) : route('admin.customers.store') }}" style="padding: 16px;">
        @csrf
        @if($customer)
            @method('PUT')
        @endif

        @if(!$customer)
            <input type="hidden" name="customer_type" value="{{ $type }}">
        @endif

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Customer Type</label>
                <input type="text" value="{{ $type }}" disabled style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb;">
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Segment</label>
                <input name="segment" value="{{ old('segment', $customer->segment ?? ($type === 'debtor' ? 'corporate' : 'retail')) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('segment')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            @if($type === 'debtor')
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Company</label>
                    <input name="company" value="{{ old('company', $customer->company ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('company')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Contact Person</label>
                    <input name="contact_person" value="{{ old('contact_person', $customer->contact_person ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('contact_person')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
            @else
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Name</label>
                    <input name="name" value="{{ old('name', $customer->name ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">WhatsApp</label>
                    <input name="whatsapp" value="{{ old('whatsapp', $customer->whatsapp ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @error('whatsapp')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
            @endif

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Email</label>
                <input name="email" value="{{ old('email', $customer->email ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('email')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Phone</label>
                <input name="phone" value="{{ old('phone', $customer->phone ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('phone')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Address</label>
                <input name="address" value="{{ old('address', $customer->address ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('address')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Payment Terms</label>
                <input name="payment_terms" value="{{ old('payment_terms', $customer->payment_terms ?? 'COD') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('payment_terms')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Credit Limit</label>
                <input name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit ?? 0) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('credit_limit')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Credit Period (days)</label>
                <input name="credit_period" value="{{ old('credit_period', $customer->credit_period ?? 0) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('credit_period')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <label style="display:block; font-weight:600; margin-bottom:6px;">Notes</label>
            <textarea name="notes" rows="3" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('notes', $customer->notes ?? '') }}</textarea>
            @error('notes')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-top: 16px; display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Save</button>
            @if($customer)
                <a class="btn-icon" href="{{ route('admin.customers.ledger', $customer) }}">View Ledger</a>
            @endif
        </div>
    </form>
</div>
@endsection
