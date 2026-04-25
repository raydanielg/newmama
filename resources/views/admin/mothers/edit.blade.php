@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Edit Mother</h3>
        <p>Update intake record.</p>
    </div>
    <div class="header-actions" style="display:flex; gap:10px;">
        <a href="{{ route('admin.mothers.show', $mother) }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.mothers.update', $mother) }}" style="padding:16px;">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:700; margin-bottom:6px;">Full Name</label>
                <input name="full_name" value="{{ old('full_name', $mother->full_name) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('full_name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">WhatsApp Number</label>
                <input name="whatsapp_number" value="{{ old('whatsapp_number', $mother->whatsapp_number) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('whatsapp_number')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Country</label>
                @php($countries = \App\Models\Country::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get())
                <select name="country_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">—</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}" {{ (string) old('country_id', $mother->country_id) === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('country_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Region ID</label>
                <input type="number" name="region_id" value="{{ old('region_id', $mother->region_id) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('region_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">District ID</label>
                <input type="number" name="district_id" value="{{ old('district_id', $mother->district_id) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('district_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Status</label>
                <select name="status" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @foreach(['pregnant' => 'Pregnant', 'new_parent' => 'New Parent', 'trying' => 'Trying'] as $k => $v)
                        <option value="{{ $k }}" {{ old('status', $mother->status) === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('status')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">EDD Date</label>
                <input type="date" name="edd_date" value="{{ old('edd_date', optional($mother->edd_date)->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('edd_date')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Baby Age (Months)</label>
                <input type="number" min="0" max="24" name="baby_age" value="{{ old('baby_age', $mother->baby_age) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('baby_age')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:700; margin-bottom:6px;">Trying Duration</label>
                <input name="trying_duration" value="{{ old('trying_duration', $mother->trying_duration) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('trying_duration')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="margin-top:16px; display:flex; gap:10px;">
            <button class="btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection
