@extends('layouts.app')

@section('content')
@php
    $shareUrl = urlencode(request()->fullUrl());
    $shareTitle = urlencode($article->title);
@endphp

<div class="landing-body">
    @include('landing.partials.header')

    <article class="article-details-section">
        <div class="landing-container">
            <nav class="article-breadcrumb animate__animated animate__fadeIn">
                <a href="{{ url('/') }}">Home</a>
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="breadcrumb-sep"><path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <a href="{{ route('articles') }}">Articles</a>
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="breadcrumb-sep"><path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>Details</span>
            </nav>

            <div class="article-main-layout">
                <div class="article-content-wrapper">
                    <header class="article-details-header animate__animated animate__fadeInUp">
                        <div class="article-meta-top">
                            <span class="article-category-badge">{{ $article->category }}</span>
                            @if($article->age_range)
                                <span class="article-age-badge">{{ $article->age_range }}</span>
                            @endif
                        </div>
                        <h1 class="article-details-title">{{ $article->title }}</h1>
                        
                        <div class="article-author-info">
                            <div class="author-avatar">
                                <img src="{{ asset('LOGO-MALKIA-KONNECT-removebg-preview.png') }}" alt="Malkia Konnect">
                            </div>
                            <div class="author-details">
                                <span class="author-name">Malkia Konnect Team</span>
                                <span class="publish-date">{{ \Carbon\Carbon::parse($article->published_at)->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </header>

                    <div class="article-featured-image-small animate__animated animate__fadeInUp">
                        <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
                    </div>

                    <div class="article-content-body animate__animated animate__fadeInUp">
                        {!! $article->content !!}
                    </div>

                    <div class="article-social-footer animate__animated animate__fadeInUp">
                        <h4>Share this with other mothers:</h4>
                        <div class="share-links-row">
                            <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" class="share-btn wa" target="_blank">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.353-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.149-1.613a11.881 11.881 0 005.899 1.558h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" class="share-btn tw" target="_blank">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" class="share-btn fb" target="_blank">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                        </div>
                    </div>
                </div>

                <aside class="article-sidebar animate__animated animate__fadeInRight">
                    {{-- Related Articles Section --}}
                    <div class="sidebar-block">
                        <div class="sidebar-block-header">
                            <h4 class="sidebar-block-title">Related Articles</h4>
                        </div>
                        <div class="sidebar-related-list">
                            @foreach($relatedArticles as $related)
                                @if($related->id !== $article->id)
                                    <a href="{{ route('articles.show', $related->slug) }}" class="sidebar-related-item">
                                        <div class="related-thumb">
                                            <img src="{{ asset($related->image) }}" alt="{{ $related->title }}">
                                        </div>
                                        <div class="related-info">
                                            <h5 class="related-title">{{ $related->title }}</h5>
                                            <span class="related-category-badge">{{ $related->category }}</span>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Categories Section --}}
                    <div class="sidebar-block">
                        <div class="sidebar-block-header">
                            <h4 class="sidebar-block-title">Categories</h4>
                        </div>
                        <div class="sidebar-categories-list">
                            @foreach($articleCategories as $cat)
                                <a href="{{ route('articles.category', $cat->slug) }}" class="sidebar-cat-item">
                                    <span class="sidebar-cat-name">{{ $cat->name }}</span>
                                    <span class="sidebar-cat-count">{{ $cat->articles_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="sidebar-card newsletter-sidebar">
                        <h4>Join Malkia</h4>
                        <p>Get helpful tips for your motherhood journey.</p>
                        <form class="sidebar-newsletter">
                            <input type="email" placeholder="Email Address" required>
                            <button type="submit" class="landing-btn">Subscribe</button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    @include('landing.partials.footer')
</div>
@endsection
