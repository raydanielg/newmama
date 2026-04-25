@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $trainer ? 'Edit Trainer' : 'Add Trainer' }}</h3>
        <p>Personal details, bio, and course assignments.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.trainers.index') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ $trainer ? route('admin.elms.trainers.update', $trainer) : route('admin.elms.trainers.store') }}" style="padding: 16px;">
        @csrf
        @if($trainer)
            @method('PUT')
        @endif

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:700; margin-bottom:6px;">Full Name *</label>
                <input name="name" value="{{ old('name', $trainer->name ?? '') }}" placeholder="e.g. Dr. Jane Doe" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;" required>
                @error('name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Email</label>
                <input type="email" name="email" value="{{ old('email', $trainer->email ?? '') }}" placeholder="trainer@malkiakonnect.co.tz" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('email')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Phone</label>
                <input name="phone" value="{{ old('phone', $trainer->phone ?? '') }}" placeholder="+255 7XX XXX XXX" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('phone')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Specialization</label>
                <input name="specialization" value="{{ old('specialization', $trainer->specialization ?? '') }}" placeholder="e.g. Prenatal Care, Nutrition" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('specialization')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:700; margin-bottom:6px;">Linked User Account (Optional)</label>
                <select name="user_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">— Select User —</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ (string) old('user_id', $trainer->user_id ?? '') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:700; margin-bottom:6px;">Assigned Courses</label>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap:10px; padding:12px; background:#f9fafb; border-radius:10px; border:1px solid #e5e7eb;">
                    @php($assignedIds = old('course_ids', $trainer ? $trainer->courses->pluck('id')->toArray() : []))
                    @foreach($courses as $c)
                        <label style="display:flex; gap:10px; align-items:center; cursor:pointer;">
                            <input type="checkbox" name="course_ids[]" value="{{ $c->id }}" {{ in_array($c->id, $assignedIds) ? 'checked' : '' }}>
                            <span>{{ $c->title }}</span>
                        </label>
                    @endforeach
                </div>
                @error('course_ids')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:700; margin-bottom:6px;">Bio</label>
                <textarea name="bio" rows="4" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('bio', $trainer->bio ?? '') }}</textarea>
                @error('bio')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:flex; gap:10px; align-items:center; font-weight:700;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $trainer->is_active ?? true) ? 'checked' : '' }}>
                    Active Status
                </label>
            </div>
        </div>

        <div style="margin-top: 24px; display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Save Trainer</button>
        </div>
    </form>
</div>
@endsection
