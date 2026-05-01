@extends('layouts.app')

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(135deg, #EEF2FF 0%, #fdfbf7 100%);
        padding: 100px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .about-hero::before {
        content: '';
        position: absolute;
        top: -10%;
        right: -10%;
        width: 40%;
        height: 60%;
        background: radial-gradient(circle, rgba(30, 64, 175, 0.05) 0%, transparent 70%);
        z-index: 0;
    }
    .about-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 24px;
        position: relative;
        z-index: 1;
    }
    .about-badge {
        display: inline-block;
        padding: 6px 16px;
        background: rgba(30, 64, 175, 0.08);
        color: #1e40af;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 24px;
    }
    .about-title {
        font-size: clamp(32px, 5vw, 48px);
        font-weight: 900;
        color: #1e293b;
        line-height: 1.1;
        margin-bottom: 24px;
    }
    .about-subtitle {
        font-size: 18px;
        color: #64748b;
        line-height: 1.6;
        max-width: 600px;
    }
    .about-content-section {
        padding: 80px 0;
        background: #fff;
    }
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    .about-image-wrapper {
        position: relative;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    }
    .about-image {
        width: 100%;
        height: auto;
        display: block;
    }
    .about-text-content h2 {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 20px;
    }
    .about-text-content p {
        font-size: 16px;
        color: #475569;
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .mission-vision {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        margin-top: 40px;
    }
    .mission-card {
        background: #f8fafc;
        padding: 32px;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
    }
    .mission-card i {
        font-size: 24px;
        color: #1e40af;
        margin-bottom: 16px;
    }
    .mission-card h3 {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
    }
    .mission-card p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
    }
    .stats-section {
        padding: 60px 0;
        background: #1e40af;
        color: #fff;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        text-align: center;
    }
    .stat-item h4 {
        font-size: 36px;
        font-weight: 900;
        margin-bottom: 8px;
    }
    .stat-item p {
        font-size: 14px;
        opacity: 0.8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .about-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        .mission-vision {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }
    }
</style>
@endpush

@section('content')
<div class="landing-body">
    @include('landing.partials.header')

    <section class="about-hero">
        <div class="about-container">
            <div class="animate__animated animate__fadeInUp">
                <span class="about-badge">About Mamacare AI</span>
                <h1 class="about-title">Revolutionizing Maternal Health Care</h1>
                <p class="about-subtitle">We are dedicated to providing every mother with the tools, knowledge, and support they need for a healthy pregnancy and a happy start to motherhood.</p>
            </div>
        </div>
    </section>

    <section class="about-content-section">
        <div class="about-container">
            <div class="about-grid">
                <div class="about-image-wrapper animate__animated animate__fadeInLeft">
                    <img src="{{ asset('LOGO-MALKIA-KONNECT.jpg') }}" alt="Mamacare Support" class="about-image">
                </div>
                <div class="about-text-content animate__animated animate__fadeInRight">
                    <h2>Our Story</h2>
                    <p>Mamacare AI was born out of a simple yet powerful realization: maternal health care should be accessible, personalized, and proactive. In many regions, mothers face challenges in accessing timely health guidance and monitoring.</p>
                    <p>By leveraging Artificial Intelligence and the ubiquity of WhatsApp, we've created a platform that bridges the gap between clinical care and home health management. Our system provides real-time monitoring, emergency support, and a wealth of trusted health information at your fingertips.</p>
                    
                    <div class="mission-vision">
                        <div class="mission-card">
                            <i class="fas fa-heart"></i>
                            <h3>Our Mission</h3>
                            <p>To empower mothers globally through innovative AI technology that ensures safety, health, and peace of mind.</p>
                        </div>
                        <div class="mission-card">
                            <i class="fas fa-eye"></i>
                            <h3>Our Vision</h3>
                            <p>A world where every pregnancy is monitored, every mother is supported, and no life is lost due to lack of health information.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="about-container">
            <div class="stats-grid animate__animated animate__fadeIn">
                <div class="stat-item">
                    <h4>2.8k+</h4>
                    <p>Mothers Supported</p>
                </div>
                <div class="stat-item">
                    <h4>24/7</h4>
                    <p>AI Support</p>
                </div>
                <div class="stat-item">
                    <h4>50+</h4>
                    <p>Health Partners</p>
                </div>
                <div class="stat-item">
                    <h4>100%</h4>
                    <p>Care Commitment</p>
                </div>
            </div>
        </div>
    </section>

    @include('landing.partials.footer')
</div>
@endsection
