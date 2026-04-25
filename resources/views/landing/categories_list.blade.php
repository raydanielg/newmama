@extends('layouts.app')

@section('content')
<div class="landing-body">
    @include('landing.partials.header')

    <div class="landing-section">
        <div class="landing-container">
            <div class="articles-header animate__animated animate__fadeInUp">
                <div class="articles-header-left">
                    <h1 class="landing-h1">All Categories</h1>
                    <div class="articles-underline"></div>
                </div>
            </div>

            <div class="categories-grid">
                @forelse($categories as $category)
                    <a href="{{ route('articles.category', $category->slug) }}" class="category-card animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="category-icon-wrapper">
                            <span class="category-icon">
                                @if($category->icon == 'heart')
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor"/></svg>
                                @elseif($category->icon == 'baby')
                                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2v20M2 12h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                @endif
                            </span>
                        </div>
                        <div class="category-info">
                            <h3 class="category-name">{{ $category->name }}</h3>
                            <p class="category-desc">{{ $category->description }}</p>
                            <div class="category-count">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="count-icon"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" fill="currentColor"/></svg>
                                {{ $category->articles_count ?? 0 }} Articles
                            </div>
                        </div>
                    </a>
                @empty
                    <p>No categories found.</p>
                @endforelse
            </div>
        </div>
    </div>

    @include('landing.partials.footer')
</div>
@endsection
