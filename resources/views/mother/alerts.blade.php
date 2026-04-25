@extends('layouts.mother')

@section('title', 'Health Alerts - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-bell"></i>
        Health Alerts
    </h1>
    @if($unreadCount > 0)
    <span class="badge badge-red" style="font-size: 14px; padding: 8px 16px;">
        <i class="fas fa-exclamation-circle"></i> {{ $unreadCount }} Unread
    </span>
    @endif
</div>

{{-- Alert Summary Cards --}}
<div class="kpi-grid" style="margin-bottom: 24px;">
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $mother->healthAlerts()->unresolved()->critical()->count() }}</div>
        <div class="kpi-label">Critical Alerts</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon orange">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $mother->healthAlerts()->unresolved()->where('severity', 'high')->count() }}</div>
        <div class="kpi-label">High Priority</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $mother->healthAlerts()->where('is_resolved', true)->count() }}</div>
        <div class="kpi-label">Resolved</div>
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon blue">
                <i class="fas fa-bell"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $alerts->total() }}</div>
        <div class="kpi-label">Total Alerts</div>
    </div>
</div>

{{-- Alerts List --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            All Alerts
        </h3>
    </div>
    
    @if($alerts->count() > 0)
    <div style="display: flex; flex-direction: column; gap: 16px;">
        @foreach($alerts as $alert)
        <div style="padding: 24px; border-radius: 16px; background: {{ $alert->severity === 'critical' ? '#fef2f2' : ($alert->severity === 'high' ? '#fff7ed' : ($alert->severity === 'medium' ? '#fefce8' : '#eff6ff')) }}; border-left: 5px solid {{ $alert->severity === 'critical' ? '#ef4444' : ($alert->severity === 'high' ? '#f97316' : ($alert->severity === 'medium' ? '#eab308' : '#3b82f6')) }}; position: relative;">
            <div style="display: flex; align-items: flex-start; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $alert->severity === 'critical' ? '#fee2e2' : ($alert->severity === 'high' ? '#ffedd5' : ($alert->severity === 'medium' ? '#fef9c3' : '#dbeafe')) }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas {{ $alert->icon }}" style="font-size: 20px; color: {{ $alert->severity === 'critical' ? '#dc2626' : ($alert->severity === 'high' ? '#ea580c' : ($alert->severity === 'medium' ? '#ca8a04' : '#2563eb')) }};"></i>
                </div>
                
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px; flex-wrap: wrap;">
                        <h4 style="font-size: 16px; font-weight: 600; color: var(--dark);">{{ $alert->alert_type_label }}</h4>
                        <span class="badge badge-{{ $alert->severity === 'critical' ? 'red' : ($alert->severity === 'high' ? 'orange' : ($alert->severity === 'medium' ? 'orange' : 'blue')) }}">
                            {{ ucfirst($alert->severity) }}
                        </span>
                        @if(!$alert->is_read)
                        <span class="badge badge-gray">New</span>
                        @endif
                    </div>
                    
                    <p style="color: var(--gray); line-height: 1.6; margin-bottom: 12px;">
                        {{ $alert->message }}
                    </p>
                    
                    <div style="background: white; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                        <div style="font-size: 12px; font-weight: 600; color: var(--gray); text-transform: uppercase; margin-bottom: 4px;">Recommendation</div>
                        <div style="color: var(--dark);">{{ $alert->recommendation }}</div>
                    </div>
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div style="font-size: 12px; color: var(--gray);">
                            <i class="far fa-clock"></i> {{ $alert->created_at->diffForHumans() }}
                            @if($alert->is_resolved)
                            <span style="margin-left: 12px; color: var(--success);">
                                <i class="fas fa-check"></i> Resolved {{ $alert->resolved_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                        
                        <div style="display: flex; gap: 8px;">
                            @if(!$alert->is_read)
                            <form action="{{ route('mother.alerts.read', $alert) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                    <i class="fas fa-check"></i> Mark Read
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div style="margin-top: 24px;">
        {{ $alerts->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 80px 20px; color: var(--gray);">
        <i class="fas fa-bell-slash" style="font-size: 64px; margin-bottom: 24px; opacity: 0.3;"></i>
        <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px; color: var(--dark);">No Health Alerts</h3>
        <p style="max-width: 400px; margin: 0 auto;">
            Great news! You have no active health alerts. Keep maintaining your healthy habits and regular checkups.
        </p>
    </div>
    @endif
</div>

{{-- Info Section --}}
<div style="margin-top: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
    <div class="card" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: #dc2626;"></i>
            <h4 style="font-weight: 600;">Critical Alerts</h4>
        </div>
        <p style="font-size: 14px; color: var(--gray);">
            Require immediate attention. Contact your healthcare provider right away or visit the nearest clinic.
        </p>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <i class="fas fa-exclamation-circle" style="font-size: 24px; color: #ea580c;"></i>
            <h4 style="font-weight: 600;">High Priority</h4>
        </div>
        <p style="font-size: 14px; color: var(--gray);">
            Important concerns that need attention soon. Schedule a checkup within 24-48 hours.
        </p>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <i class="fas fa-info-circle" style="font-size: 24px; color: #ca8a04;"></i>
            <h4 style="font-weight: 600;">Medium Priority</h4>
        </div>
        <p style="font-size: 14px; color: var(--gray);">
            Things to monitor and discuss at your next appointment. Keep tracking your symptoms.
        </p>
    </div>
</div>
@endsection
