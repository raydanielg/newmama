@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>ELMS Articles</h3>
        <p>ELMS learning articles (separate from the public site content).</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.elms.articles.create') }}" class="btn-primary" style="text-decoration:none;">Add Article</a>
    </div>
</div>

<div class="content-card" style="margin-bottom:16px;">
    <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:space-between; padding: 14px;">
        <form method="GET" action="{{ route('admin.elms.articles') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title, slug, level..." style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; min-width:240px;">

            <select name="category_id" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (string) request('category_id') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="course_id" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="">All Courses</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ (string) request('course_id') === (string) $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                @endforeach
            </select>

            <select name="published" style="padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px;">
                <option value="all" {{ $filterPublished === 'all' ? 'selected' : '' }}>All</option>
                <option value="published" {{ $filterPublished === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ $filterPublished === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>

            <button class="btn-primary" type="submit">Filter</button>
        </form>

        <div style="font-size:12px; color:#6b7280;">Total: <strong>{{ number_format($articles->total()) }}</strong></div>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th style="width:160px;">Category</th>
                    <th style="width:200px;">Course</th>
                    <th style="width:120px;">Status</th>
                    <th style="width:150px;">Published</th>
                    <th style="width:240px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $a)
                <tr>
                    <td>
                        <div style="font-weight:800;">{{ $a->title }}</div>
                        <div style="font-size:12px; color:#6b7280; font-family:var(--mono);">/{{ $a->slug }}</div>
                    </td>
                    <td>{{ $a->category?->name ?: '—' }}</td>
                    <td>{{ $a->course?->title ?: '—' }}</td>
                    <td>
                        <span class="badge {{ $a->is_published ? 'status-trying' : 'status-pregnant' }}">{{ $a->is_published ? 'PUBLISHED' : 'DRAFT' }}</span>
                    </td>
                    <td style="font-family:var(--mono);">{{ $a->published_at?->toDateString() ?: '—' }}</td>
                    <td>
                        <a class="btn-icon" href="{{ route('admin.elms.articles.edit', $a) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.elms.articles.toggle-publish', $a) }}" style="display:inline;">
                            @csrf
                            <button class="btn-icon" type="submit">{{ $a->is_published ? 'Unpublish' : 'Publish' }}</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 18px;">No ELMS articles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
