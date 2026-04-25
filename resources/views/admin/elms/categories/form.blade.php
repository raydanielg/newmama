@extends('layouts.admin')

@section('title', $category ? 'Edit Category' : 'Add Category')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $category ? 'Edit Category' : 'Add Category' }}</h3>
        <p>Used for ELMS articles (separate from public articles).</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.categories') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ $category ? route('admin.elms.categories.update', $category) : route('admin.elms.categories.store') }}" style="padding: 16px;">
        @csrf
        @if($category)
            @method('PUT')
        @endif

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Name</label>
                <input name="name" value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Compliance" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('name')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Slug (optional)</label>
                <input name="slug" value="{{ old('slug', $category->slug ?? '') }}" placeholder="auto-generated" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('slug')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Icon (optional)</label>
                <input name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="e.g. fa-solid fa-book" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('icon')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Description</label>
                <textarea name="description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('description', $category->description ?? '') }}</textarea>
                @error('description')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            @if($category)
            <div style="grid-column: span 2;">
                <label style="display:flex; gap:10px; align-items:center; font-weight:700;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>
            @endif
        </div>

        <div style="margin-top: 16px; display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
