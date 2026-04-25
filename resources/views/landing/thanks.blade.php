@extends('layouts.app')

@section('content')
@php
    $lang = request('lang', session('locale', 'sw'));
    $motherName = request('name', session('mother_name', 'Mama'));
    $mkNumber = session('mk_number');
@endphp
<div class="thanks-page">
    <div class="landing-container">
        <div class="thanks-card animate__animated animate__fadeInUp">
            <div class="thanks-icon-wrapper animate__animated animate__bounceIn animate__delay-1s">
                <div class="thanks-icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" width="50" height="50" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
            
            @if($lang === 'en')
                <h1 class="thanks-title">Welcome to the family, <br><span class="highlight-name">Mama {{ $motherName }}!</span></h1>
                @if($mkNumber)
                    <div class="mk-number-badge mb-4">
                        <span class="label">Your MK Number:</span>
                        <span class="number">{{ $mkNumber }}</span>
                    </div>
                @endif
                <p class="thanks-text">
                    Your journey with Malkia Konnect has officially started. We are so happy to have you with us!
                </p>

                <div class="thanks-info-box">
                    <div class="info-item">
                        <span class="info-emoji">📱</span>
                        <div class="info-content">
                            <h4>Check WhatsApp</h4>
                            <p>A welcome message will be sent to your number shortly.</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-emoji">💖</span>
                        <div class="info-content">
                            <h4>Save our Number</h4>
                            <p>Save Malkia Konnect so you don't miss our tips.</p>
                        </div>
                    </div>
                </div>

                <div class="thanks-actions">
                    <a href="{{ url('/') }}" class="landing-btn">Back Home</a>
                    <a href="{{ route('articles') }}" class="landing-btn landing-btn-ghost">Explore Articles</a>
                </div>
            @else
                <h1 class="thanks-title">Karibu kwenye familia, <br><span class="highlight-name">Mama {{ $motherName }}!</span></h1>
                @if($mkNumber)
                    <div class="mk-number-badge mb-4">
                        <span class="label">Namba yako ya MK:</span>
                        <span class="number">{{ $mkNumber }}</span>
                    </div>
                @endif
                <p class="thanks-text">
                    Safari yako na Malkia Konnect imeanza rasmi. Tumefurahi sana kuwa nawe!
                </p>

                <div class="thanks-info-box">
                    <div class="info-item">
                        <span class="info-emoji">📱</span>
                        <div class="info-content">
                            <h4>Angalia WhatsApp</h4>
                            <p>Ujumbe wa kukaribishwa utatumwa kwenye namba yako hivi karibuni.</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-emoji">💖</span>
                        <div class="info-content">
                            <h4>Hifadhi Namba</h4>
                            <p>Hifadhi namba ya Malkia Konnect ili usipitwe na ushauri wetu.</p>
                        </div>
                    </div>
                </div>

                <div class="thanks-actions">
                    <a href="{{ url('/') }}" class="landing-btn">Rudi Nyumbani</a>
                    <a href="{{ route('articles') }}" class="landing-btn landing-btn-ghost">Soma Makala</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
