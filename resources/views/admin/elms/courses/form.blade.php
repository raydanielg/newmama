@extends('layouts.admin')

@section('title', $course ? 'Edit Course' : 'Add Course')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $course ? 'Edit Course' : 'Add Course' }}</h3>
        <p>Course details, base price, and catalog metadata.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.courses') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ $course ? route('admin.elms.courses.update', $course) : route('admin.elms.courses.store') }}" style="padding: 16px;">
        @csrf
        @if($course)
            @method('PUT')
        @endif

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Course Title</label>
                <input name="title" value="{{ old('title', $course->title ?? '') }}" placeholder="e.g. Basic Accounting for SMEs" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('title')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Category</label>
                <input name="category" value="{{ old('category', $course->category ?? '') }}" placeholder="e.g. Finance" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('category')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Level</label>
                <input name="level" value="{{ old('level', $course->level ?? '') }}" placeholder="e.g. Beginner / Intermediate" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('level')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Duration (Hours)</label>
                <input type="number" min="0" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours ?? '') }}" placeholder="e.g. 12" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('duration_hours')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Base Price</label>
                <input type="number" step="0.01" min="0" name="base_price" value="{{ old('base_price', $course->base_price ?? 0) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('base_price')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Currency</label>
                <select name="currency" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    @php($curr = old('currency', $course->currency ?? 'TZS'))
                    @foreach(['TZS','USD'] as $cc)
                        <option value="{{ $cc }}" {{ $curr === $cc ? 'selected' : '' }}>{{ $cc }}</option>
                    @endforeach
                </select>
                @error('currency')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Description</label>
                <textarea name="description" rows="4" placeholder="Course outline, audience, requirements..." style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('description', $course->description ?? '') }}</textarea>
                @error('description')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            @if($course)
            <div style="grid-column: span 2;">
                <label style="display:flex; gap:10px; align-items:center; font-weight:700;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>
            @endif
        </div>

        @if($course)
            <div class="content-card" style="margin-top: 16px; background:#f9fafb; border:1px solid #eef2f7;">
                <div style="padding: 14px; display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px;">
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Course Code</div>
                        <div style="font-weight:800; font-family:var(--mono);">{{ $course->code }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Base Price</div>
                        <div style="font-weight:800;">{{ $course->currency }} {{ number_format((float) $course->base_price, 2) }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Status</div>
                        <div style="font-weight:800;">{{ $course->is_active ? 'ACTIVE' : 'INACTIVE' }}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Created</div>
                        <div style="font-weight:800;">{{ $course->created_at?->format('M d, Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div style="margin-top: 16px; display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Save</button>
            @if($course)
                <a class="btn-icon" href="{{ route('admin.elms.courses.show', $course) }}">View Details</a>
            @endif
        </div>
    </form>
</div>
@endsection
