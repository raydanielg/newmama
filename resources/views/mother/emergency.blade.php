@extends('layouts.mother')

@section('title', 'Emergency Help - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title" style="color: var(--danger);">
        <i class="fas fa-first-aid"></i>
        Emergency Help
    </h1>
</div>

{{-- Emergency Banner --}}
<div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 32px; border-radius: 20px; margin-bottom: 24px; text-align: center;">
    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 16px;"></i>
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 8px;">If this is a life-threatening emergency</h2>
    <p style="font-size: 16px; opacity: 0.95; margin-bottom: 20px;">Call emergency services immediately or go to the nearest hospital</p>
    
    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="tel:114" style="display: inline-flex; align-items: center; gap: 12px; background: white; color: #dc2626; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 18px;">
            <i class="fas fa-phone-alt"></i>
            Call 114 (Ambulance)
        </a>
        <a href="tel:115" style="display: inline-flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.2); color: white; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 18px;">
            <i class="fas fa-fire"></i>
            115 (Fire/Police)
        </a>
    </div>
</div>

{{-- When to Seek Emergency Care --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title" style="color: var(--danger);">
            <i class="fas fa-ambulance"></i>
            When to Seek Emergency Care Immediately
        </h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
        <div style="padding: 20px; background: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i class="fas fa-droplet" style="color: #ef4444; font-size: 20px;"></i>
                <h4 style="font-weight: 600;">Heavy Bleeding</h4>
            </div>
            <p style="font-size: 14px; color: var(--gray);">Any heavy bleeding, especially with pain or dizziness</p>
        </div>
        
        <div style="padding: 20px; background: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i class="fas fa-head-side-virus" style="color: #ef4444; font-size: 20px;"></i>
                <h4 style="font-weight: 600;">Severe Headache</h4>
            </div>
            <p style="font-size: 14px; color: var(--gray);">Severe headache with vision changes or upper belly pain</p>
        </div>
        
        <div style="padding: 20px; background: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i class="fas fa-heart-pulse" style="color: #ef4444; font-size: 20px;"></i>
                <h4 style="font-weight: 600;">Chest Pain</h4>
            </div>
            <p style="font-size: 14px; color: var(--gray);">Chest pain, trouble breathing, or rapid heartbeat</p>
        </div>
        
        <div style="padding: 20px; background: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i class="fas fa-baby" style="color: #ef4444; font-size: 20px;"></i>
                <h4 style="font-weight: 600;">No Baby Movement</h4>
            </div>
            <p style="font-size: 14px; color: var(--gray);">No baby movements felt for more than 24 hours after 28 weeks</p>
        </div>
        
        <div style="padding: 20px; background: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i class="fas fa-tint" style="color: #ef4444; font-size: 20px;"></i>
                <h4 style="font-weight: 600;">Water Breaking</h4>
            </div>
            <p style="font-size: 14px; color: var(--gray);">Sudden gush or continuous leaking of fluid from vagina</p>
        </div>
        
        <div style="padding: 20px; background: #fef2f2; border-radius: 12px; border-left: 4px solid #ef4444;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i class="fas fa-temperature-high" style="color: #ef4444; font-size: 20px;"></i>
                <h4 style="font-weight: 600;">High Fever</h4>
            </div>
            <p style="font-size: 14px; color: var(--gray);">Fever over 38°C (100.4°F) with chills</p>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="grid grid-2" style="margin-bottom: 24px;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-hospital"></i>
                Nearby Health Facilities
            </h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="padding: 16px; background: var(--light); border-radius: 12px;">
                <div style="font-weight: 600; margin-bottom: 4px;">Mama Care Clinic</div>
                <div style="font-size: 14px; color: var(--gray); margin-bottom: 8px;">
                    <i class="fas fa-map-marker-alt"></i> 123 Health Street, Dar es Salaam
                </div>
                <a href="tel:+255123456789" style="display: inline-flex; align-items: center; gap: 8px; color: var(--secondary); text-decoration: none; font-size: 14px;">
                    <i class="fas fa-phone"></i> +255 123 456 789
                </a>
            </div>
            <div style="padding: 16px; background: var(--light); border-radius: 12px;">
                <div style="font-weight: 600; margin-bottom: 4px;">Regional Hospital</div>
                <div style="font-size: 14px; color: var(--gray); margin-bottom: 8px;">
                    <i class="fas fa-map-marker-alt"></i> Hospital Road, City Center
                </div>
                <a href="tel:+255987654321" style="display: inline-flex; align-items: center; gap: 8px; color: var(--secondary); text-decoration: none; font-size: 14px;">
                    <i class="fas fa-phone"></i> +255 987 654 321
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-md"></i>
                Contact Your Provider
            </h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="tel:{{ $mother?->whatsapp_number ?? '#' }}" class="btn btn-primary" style="justify-content: center;">
                <i class="fas fa-phone"></i> Call Your Doctor
            </a>
            <a href="https://wa.me/{{ $mother?->whatsapp_number ?? '' }}" target="_blank" class="btn btn-secondary" style="justify-content: center;">
                <i class="fab fa-whatsapp"></i> WhatsApp Support
            </a>
            <div style="padding: 16px; background: #f0fdf4; border-radius: 12px; text-align: center;">
                <div style="font-size: 12px; color: var(--gray); margin-bottom: 4px;">Available 24/7</div>
                <div style="font-weight: 600; color: var(--success);">
                    <i class="fas fa-check-circle"></i> Emergency Support Active
                </div>
            </div>
        </div>
    </div>
</div>

{{-- First Aid Tips --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-kit-medical"></i>
            First Aid While Waiting for Help
        </h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div style="text-align: center; padding: 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-bed" style="font-size: 28px; color: var(--primary);"></i>
            </div>
            <h4 style="font-weight: 600; margin-bottom: 8px;">Lie on Your Left Side</h4>
            <p style="font-size: 14px; color: var(--gray);">This improves blood flow to the baby</p>
        </div>
        
        <div style="text-align: center; padding: 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #dbeafe; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-glass-water" style="font-size: 28px; color: var(--secondary);"></i>
            </div>
            <h4 style="font-weight: 600; margin-bottom: 8px;">Stay Hydrated</h4>
            <p style="font-size: 14px; color: var(--gray);">Sip water if you're able to swallow</p>
        </div>
        
        <div style="text-align: center; padding: 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #dcfce7; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-lungs" style="font-size: 28px; color: var(--success);"></i>
            </div>
            <h4 style="font-weight: 600; margin-bottom: 8px;">Breathe Deeply</h4>
            <p style="font-size: 14px; color: var(--gray);">Slow, deep breaths to stay calm</p>
        </div>
        
        <div style="text-align: center; padding: 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #fef9c3; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-people-arrows" style="font-size: 28px; color: #ca8a04;"></i>
            </div>
            <h4 style="font-weight: 600; margin-bottom: 8px;">Have Someone Stay</h4>
            <p style="font-size: 14px; color: var(--gray);">Don't be alone during an emergency</p>
        </div>
    </div>
</div>
@endsection
