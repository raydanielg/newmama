@extends('layouts.admin')

@section('title', 'Articles Management')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>Articles List</h3>
        <p>Manage your educational content and tips for mothers.</p>
    </div>
    <div class="header-actions">
        <button class="btn-primary">Add New Article</button>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(Illuminate\Support\Facades\DB::table('articles')->join('article_categories', 'articles.category_id', '=', 'article_categories.id')->select('articles.*', 'article_categories.name as category_name')->get() as $article)
                <tr>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->category_name }}</td>
                    <td>Admin</td>
                    <td>124</td>
                    <td>{{ date('M d, Y', strtotime($article->created_at)) }}</td>
                    <td>
                        <button class="btn-icon">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
