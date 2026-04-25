@extends('layouts.admin')

@section('title', $article ? 'Edit ELMS Article' : 'Add ELMS Article')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $article ? 'Edit ELMS Article' : 'Add ELMS Article' }}</h3>
        <p>Write ELMS learning content (independent from public articles).</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.articles') }}" class="btn-primary" style="text-decoration:none;">Back</a>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ $article ? route('admin.elms.articles.update', $article) : route('admin.elms.articles.store') }}" style="padding: 16px;">
        @csrf
        @if($article)
            @method('PUT')
        @endif

        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Title</label>
                <input name="title" value="{{ old('title', $article->title ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('title')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Slug (optional)</label>
                <input name="slug" value="{{ old('slug', $article->slug ?? '') }}" placeholder="auto-generated" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('slug')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Level</label>
                @php($levels = \App\Models\ElmsLevel::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get())
                @if($levels->count() > 0)
                    <select name="level" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                        <option value="">—</option>
                        @foreach($levels as $lv)
                            <option value="{{ $lv->name }}" {{ (string) old('level', $article->level ?? '') === (string) $lv->name ? 'selected' : '' }}>{{ $lv->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input name="level" value="{{ old('level', $article->level ?? '') }}" placeholder="Beginner / Intermediate" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @endif
                @error('level')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Category</label>
                <select name="category_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">—</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) old('category_id', $article->category_id ?? '') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Course (optional)</label>
                <select name="course_id" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                    <option value="">—</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ (string) old('course_id', $article->course_id ?? '') === (string) $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                    @endforeach
                </select>
                @error('course_id')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Published At (optional)</label>
                <input type="date" name="published_at" value="{{ old('published_at', optional($article->published_at ?? null)->toDateString()) }}" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('published_at')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Thumbnail URL (optional)</label>
                <input name="thumbnail" value="{{ old('thumbnail', $article->thumbnail ?? '') }}" placeholder="https://..." style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                @error('thumbnail')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Excerpt</label>
                <textarea name="excerpt" rows="3" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                @error('excerpt')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div style="grid-column: span 2;">
                <label style="display:block; font-weight:600; margin-bottom:6px;">Content</label>
                <textarea name="content" rows="10" style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">{{ old('content', $article->content ?? '') }}</textarea>
                @error('content')<div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:flex; gap:10px; align-items:center; font-weight:700;">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published ?? false) ? 'checked' : '' }}>
                    Published
                </label>
            </div>

            <div>
                <label style="display:flex; gap:10px; align-items:center; font-weight:700;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured ?? false) ? 'checked' : '' }}>
                    Featured
                </label>
            </div>
        </div>

        <div style="margin-top: 16px; display:flex; gap:10px;">
            <button type="submit" class="btn-primary">Save</button>
            @if($article)
                <form method="POST" action="{{ route('admin.elms.articles.toggle-publish', $article) }}" style="display:inline;">
                    @csrf
                    <button class="btn-icon" type="submit">{{ $article->is_published ? 'Unpublish' : 'Publish' }}</button>
                </form>
            @endif
        </div>
    </form>
</div>
@endsection
