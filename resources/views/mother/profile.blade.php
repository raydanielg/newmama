@extends('layouts.mother')

@section('title', 'My Profile - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-user"></i>
        My Profile
    </h1>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-id-card"></i>
                Personal Information
            </h3>
        </div>
        
        <div style="display: grid; gap: 20px;">
            <div style="display: flex; justify-content: space-between; padding: 16px; background: var(--light); border-radius: 12px;">
                <span style="color: var(--gray);">Full Name</span>
                <span style="font-weight: 600;">{{ $mother->full_name }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 16px; background: var(--light); border-radius: 12px;">
                <span style="color: var(--gray);">MK Number</span>
                <span style="font-weight: 600; font-family: monospace;">{{ $mother->mk_number }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 16px; background: var(--light); border-radius: 12px;">
                <span style="color: var(--gray);">WhatsApp Number</span>
                <span style="font-weight: 600;">{{ $mother->whatsapp_number }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 16px; background: var(--light); border-radius: 12px;">
                <span style="color: var(--gray);">Status</span>
                <span class="badge badge-{{ $mother->status_color }}">{{ $mother->status_label }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-map-marker-alt"></i>
                Location
            </h3>
        </div>
        
        <div style="display: grid; gap: 20px;">
            <div style="display: flex; justify-content: space-between; padding: 16px; background: var(--light); border-radius: 12px;">
                <span style="color: var(--gray);">Country</span>
                <span style="font-weight: 600;">{{ $mother->country?->name ?? 'Not specified' }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 16px; background: var(--light); border-radius: 12px;">
                <span style="color: var(--gray);">Region</span>
                <span style="font-weight: 600;">{{ $mother->region?->name ?? 'Not specified' }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 16px; background: var(--light); border-radius: 12px;">
                <span style="color: var(--gray);">District</span>
                <span style="font-weight: 600;">{{ $mother->district?->name ?? 'Not specified' }}</span>
            </div>
        </div>
    </div>
</div>

@if($mother->status === 'pregnant')
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-baby"></i>
            Pregnancy Information
        </h3>
    </div>
    
    <div class="grid grid-3">
        <div style="text-align: center; padding: 24px; background: linear-gradient(135deg, var(--primary-light) 0%, #fce7f3 100%); border-radius: 16px;">
            <i class="fas fa-calendar-day" style="font-size: 32px; color: var(--primary); margin-bottom: 12px;"></i>
            <div style="font-size: 28px; font-weight: 700; color: var(--primary-dark);">
                {{ $mother->weeks_pregnant ?? 'N/A' }}
            </div>
            <div style="font-size: 14px; color: var(--gray);">Weeks Pregnant</div>
        </div>
        
        <div style="text-align: center; padding: 24px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 16px;">
            <i class="fas fa-heart" style="font-size: 32px; color: var(--secondary); margin-bottom: 12px;"></i>
            <div style="font-size: 28px; font-weight: 700; color: var(--secondary);">
                {{ $mother->trimester ?? 'N/A' }}
            </div>
            <div style="font-size: 14px; color: var(--gray);">Trimester</div>
        </div>
        
        <div style="text-align: center; padding: 24px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 16px;">
            <i class="fas fa-calendar-check" style="font-size: 32px; color: var(--success); margin-bottom: 12px;"></i>
            <div style="font-size: 20px; font-weight: 700; color: var(--success);">
                {{ $mother->edd_date?->format('M d, Y') ?? 'N/A' }}
            </div>
            <div style="font-size: 14px; color: var(--gray);">Expected Due Date</div>
        </div>
    </div>
</div>
@endif

@if($mother->status === 'new_parent')
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-child"></i>
            Baby Information
        </h3>
    </div>
    
    <div style="text-align: center; padding: 40px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 16px;">
        <i class="fas fa-baby" style="font-size: 48px; color: var(--secondary); margin-bottom: 16px;"></i>
        <div style="font-size: 32px; font-weight: 700; color: var(--secondary);">
            {{ $mother->baby_age ?? 'N/A' }} months
        </div>
        <div style="font-size: 16px; color: var(--gray); margin-top: 8px;">
            Congratulations on your new baby!
        </div>
    </div>
</div>
@endif
@endsection
