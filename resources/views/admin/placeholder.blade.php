@extends('layouts.admin')

@section('title', $title ?? 'Page')

@section('admin-content')
<div class="module-header">
    <div class="header-info">
        <h3>{{ $title ?? 'Page' }}</h3>
        <p>This page is available in the menu. Implementation will be added.</p>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>{{ $title ?? 'Page' }}</h3>
    </div>
    <div style="padding: 16px;">
        Content coming soon.
    </div>
</div>
@endsection
