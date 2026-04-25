@extends('layouts.mother')

@section('title', 'Education - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-book-open"></i>
        Pregnancy Education
    </h1>
    @if($mother && $mother->weeks_pregnant)
    <span class="badge badge-pink">Week {{ $mother->weeks_pregnant }}</span>
    @endif
</div>

{{-- Weekly Content --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-star"></i>
            This Week's Content
        </h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        @foreach($articles as $article)
        <div style="padding: 24px; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); border-radius: 16px; border: 1px solid rgba(236, 72, 153, 0.2);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: white; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-{{ $article['type'] === 'development' ? 'baby' : ($article['type'] === 'nutrition' ? 'carrot' : ($article['type'] === 'fitness' ? 'person-walking' : 'stethoscope')) }}" style="font-size: 20px; color: var(--primary);"></i>
                </div>
                <span class="badge badge-pink" style="text-transform: uppercase; font-size: 10px;">{{ $article['type'] }}</span>
            </div>
            <h4 style="font-weight: 600; margin-bottom: 8px; color: var(--dark);">{{ $article['title'] }}</h4>
            <p style="font-size: 14px; color: var(--gray); margin-bottom: 16px;">{{ $article['content'] }}</p>
            <button class="btn btn-primary" style="width: 100%; padding: 10px;">
                <i class="fas fa-book-reader"></i> Read More
            </button>
        </div>
        @endforeach
    </div>
</div>

{{-- Education Categories --}}
<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-apple-alt" style="color: #22c55e;"></i>
                Nutrition & Diet
            </h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-leaf" style="color: #22c55e;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Healthy Eating During Pregnancy</div>
                    <div style="font-size: 12px; color: var(--gray);">Essential nutrients for you and baby</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-ban" style="color: #ef4444;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Foods to Avoid</div>
                    <div style="font-size: 12px; color: var(--gray);">What not to eat while pregnant</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-weight" style="color: #f59e0b;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Healthy Weight Gain</div>
                    <div style="font-size: 12px; color: var(--gray);">How much weight should you gain?</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-running" style="color: #3b82f6;"></i>
                Exercise & Fitness
            </h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-walking" style="color: #3b82f6;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Safe Exercises</div>
                    <div style="font-size: 12px; color: var(--gray);">Recommended activities by trimester</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-spa" style="color: #a855f7;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Prenatal Yoga</div>
                    <div style="font-size: 12px; color: var(--gray);">Gentle stretches and breathing</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-bed" style="color: #6366f1;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Rest & Recovery</div>
                    <div style="font-size: 12px; color: var(--gray);">Importance of sleep and rest</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-baby" style="color: #ec4899;"></i>
                Baby Development
            </h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-calendar-alt" style="color: #ec4899;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Week-by-Week Growth</div>
                    <div style="font-size: 12px; color: var(--gray);">How your baby develops</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-heartbeat" style="color: #ef4444;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Kick Counting Guide</div>
                    <div style="font-size: 12px; color: var(--gray);">Monitoring baby movements</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-brain" style="color: #8b5cf6;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Brain Development</div>
                    <div style="font-size: 12px; color: var(--gray);">Supporting cognitive growth</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-hospital" style="color: #f97316;"></i>
                Labor & Delivery
            </h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-suitcase" style="color: #f97316;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Hospital Bag Checklist</div>
                    <div style="font-size: 12px; color: var(--gray);">What to pack for delivery</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-bell" style="color: #ef4444;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Signs of Labor</div>
                    <div style="font-size: 12px; color: var(--gray);">When to go to the hospital</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 16px; background: var(--light); border-radius: 12px; text-decoration: none; color: var(--dark);">
                <i class="fas fa-hand-holding-heart" style="color: #ec4899;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">Pain Management</div>
                    <div style="font-size: 12px; color: var(--gray);">Options for labor pain relief</div>
                </div>
                <i class="fas fa-chevron-right" style="color: var(--gray);"></i>
            </a>
        </div>
    </div>
</div>

{{-- Video Resources --}}
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-video"></i>
            Video Resources
        </h3>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div style="text-align: center; padding: 32px; background: var(--light); border-radius: 16px;">
            <i class="fas fa-play-circle" style="font-size: 48px; color: var(--primary); margin-bottom: 16px;"></i>
            <h4 style="font-weight: 600; margin-bottom: 8px;">Breathing Techniques</h4>
            <p style="font-size: 14px; color: var(--gray);">Learn breathing exercises for labor</p>
        </div>
        <div style="text-align: center; padding: 32px; background: var(--light); border-radius: 16px;">
            <i class="fas fa-play-circle" style="font-size: 48px; color: var(--secondary); margin-bottom: 16px;"></i>
            <h4 style="font-weight: 600; margin-bottom: 8px;">Breastfeeding Basics</h4>
            <p style="font-size: 14px; color: var(--gray);">Getting started with breastfeeding</p>
        </div>
        <div style="text-align: center; padding: 32px; background: var(--light); border-radius: 16px;">
            <i class="fas fa-play-circle" style="font-size: 48px; color: var(--success); margin-bottom: 16px;"></i>
            <h4 style="font-weight: 600; margin-bottom: 8px;">Newborn Care</h4>
            <p style="font-size: 14px; color: var(--gray);">Essential tips for new parents</p>
        </div>
    </div>
</div>
@endsection
