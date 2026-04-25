@extends('layouts.app')

@section('content')
<div class="landing-body">
    @include('landing.partials.header')

    <div class="landing-section">
        <div class="landing-container">
            <div class="articles-header animate__animated animate__fadeInUp">
                <div class="articles-header-left">
                    <h1 class="landing-h1">All Articles</h1>
                    <div class="articles-underline"></div>
                </div>
            </div>

            <div class="articles-grid">
                @forelse($articles as $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="article-card-link">
                        <div class="article-card animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="article-image-wrapper">
                                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="article-img">
                                @if($article->age_range)
                                    <span class="age-badge">{{ $article->age_range }}</span>
                                @endif
                            </div>
                            <div class="article-content">
                                <h3 class="article-title">{{ $article->title }}</h3>
                                <div class="article-meta">
                                    <span class="article-category">{{ $article->category }}</span>
                                    <span class="article-date">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="meta-icon">
                                            <path d="M8 2v3M16 2v3M3.5 9h17M21 8.5V17c0 2.2-1.8 4-4 4H7c-2.2 0-4-1.8-4-4V8.5c0-2.2 1.8-4 4-4h10c2.2 0 4 1.8 4 4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($article->published_at)->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>No articles found.</p>
                @endforelse
            </div>
        </div>
    </div>

    @include('landing.partials.footer')
</div>
@endsection
