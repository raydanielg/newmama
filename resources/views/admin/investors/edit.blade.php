@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title }}</h3>
        <p>{{ $investor->investor_number }} · Update profile details and status.</p>
    </div>
    <div class="header-actions">
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.show', $investor) }}">Back to Profile</a>
        <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors') }}">Overview</a>
    </div>
</div>

<div class="content-card" style="padding:16px;">
    <div class="card-header" style="padding:0; margin-bottom:10px;"><h3>Investor Details</h3></div>

    <form method="POST" action="{{ route('admin.investors.update', $investor) }}" style="display:grid; grid-template-columns: 1fr 220px 220px; gap:12px; align-items:end;">
        @csrf
        @method('PUT')

        <div style="grid-column: 1 / -1;">
            <label class="form-label">Name</label>
            <input name="name" required value="{{ old('name', $investor->name) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>

        <div>
            <label class="form-label">Phone</label>
            <input name="phone" value="{{ old('phone', $investor->phone) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $investor->email) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>

        <div>
            <label class="form-label">ID Number</label>
            <input name="id_number" value="{{ old('id_number', $investor->id_number) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>

        <div style="grid-column: 1 / -1;">
            <label class="form-label">Address</label>
            <input name="address" value="{{ old('address', $investor->address) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
        </div>

        <div>
            <label class="form-label">Status</label>
            <select name="status" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="active" {{ old('status', $investor->status)==='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $investor->status)==='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div>
            <label class="form-label">Balance</label>
            <input disabled value="TSh {{ number_format((float) $investor->balance, 2) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb; font-family:var(--mono); font-weight:900; text-align:right;">
        </div>

        <div>
            <label class="form-label">Investor #</label>
            <input disabled value="{{ $investor->investor_number }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb; font-family:var(--mono); font-weight:900;">
        </div>

        <div style="grid-column: 1 / -1;">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="3" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('notes', $investor->notes) }}</textarea>
        </div>

        <div style="grid-column: 1 / -1; display:flex; justify-content:flex-end; gap:10px;">
            <a class="btn-icon" style="text-decoration:none;" href="{{ route('admin.investors.show', $investor) }}">Cancel</a>
            <button class="btn-primary" type="submit">Save Changes</button>
        </div>
    </form>
</div>
@endsection
