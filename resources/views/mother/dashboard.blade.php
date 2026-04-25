@extends('layouts.mother')

@section('title', 'Dashboard - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-home"></i>
        Welcome back, {{ $mother->full_name }}
    </h1>
    <div class="header-actions">
        <span style="color: var(--gray); font-size: 14px;">
            <i class="far fa-calendar"></i> {{ now()->format('l, F j, Y') }}
        </span>
    </div>
</div>

{{-- Critical Alerts Banner --}}
@if($metrics['critical_alerts'] > 0)
<div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 20px 24px; border-radius: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 16px;">
    <i class="fas fa-exclamation-triangle" style="font-size: 32px;"></i>
    <div style="flex: 1;">
        <div style="font-weight: 600; font-size: 16px; margin-bottom: 4px;">
            {{ $metrics['critical_alerts'] }} Critical Health Alert{{ $metrics['critical_alerts'] > 1 ? 's' : '' }}
        </div>
        <div style="font-size: 14px; opacity: 0.95;">
            Please review immediately and contact your healthcare provider if needed.
        </div>
    </div>
    <a href="{{ route('mother.alerts') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; white-space: nowrap;">
        View Alerts
    </a>
</div>
@endif

{{-- KPI Cards --}}
<div class="kpi-grid">
    @if($mother->status === 'pregnant')
    <div class="kpi-card animate__animated animate__fadeInUp" style="animation-delay: 0ms;">
        <div class="kpi-header">
            <div class="kpi-icon pink">
                <i class="fas fa-baby"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $metrics['weeks_pregnant'] ?? 'N/A' }}</div>
        <div class="kpi-label">Weeks Pregnant</div>
        @if($metrics['weeks_pregnant'])
        <div style="margin-top: 12px; font-size: 12px; color: var(--primary);">
            <i class="fas fa-info-circle"></i> Trimester {{ $metrics['trimester'] }}
        </div>
        @endif
    </div>

    <div class="kpi-card animate__animated animate__fadeInUp" style="animation-delay: 50ms;">
        <div class="kpi-header">
            <div class="kpi-icon blue">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
        <div class="kpi-value">
            @if($metrics['days_until_edd'] !== null)
                {{ $metrics['days_until_edd'] > 0 ? $metrics['days_until_edd'] : 'Overdue' }}
            @else
                N/A
            @endif
        </div>
        <div class="kpi-label">Days Until Due Date</div>
        @if($mother->edd_date)
        <div style="margin-top: 12px; font-size: 12px; color: var(--secondary);">
            <i class="fas fa-calendar"></i> {{ $mother->edd_date->format('M d, Y') }}
        </div>
        @endif
    </div>
    @endif

    <div class="kpi-card animate__animated animate__fadeInUp" style="animation-delay: 100ms;">
        <div class="kpi-header">
            <div class="kpi-icon {{ $metrics['unread_alerts'] > 0 ? 'orange' : 'green' }}">
                <i class="fas fa-bell"></i>
            </div>
            @if($metrics['unread_alerts'] > 0)
            <span class="kpi-trend down">{{ $metrics['unread_alerts'] }} new</span>
            @endif
        </div>
        <div class="kpi-value">{{ $metrics['unread_alerts'] }}</div>
        <div class="kpi-label">Unread Alerts</div>
        @if($metrics['unread_alerts'] > 0)
        <div style="margin-top: 12px;">
            <a href="{{ route('mother.alerts') }}" style="font-size: 12px; color: var(--warning); text-decoration: none;">
                <i class="fas fa-arrow-right"></i> Review now
            </a>
        </div>
        @endif
    </div>

    <div class="kpi-card animate__animated animate__fadeInUp" style="animation-delay: 150ms;">
        <div class="kpi-header">
            <div class="kpi-icon blue">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $metrics['upcoming_appointments'] }}</div>
        <div class="kpi-label">Upcoming Appointments</div>
        @if($metrics['upcoming_appointments'] > 0)
        <div style="margin-top: 12px;">
            <a href="{{ route('mother.appointments') }}" style="font-size: 12px; color: var(--secondary); text-decoration: none;">
                <i class="fas fa-arrow-right"></i> View all
            </a>
        </div>
        @endif
    </div>

    <div class="kpi-card animate__animated animate__fadeInUp" style="animation-delay: 200ms;">
        <div class="kpi-header">
            <div class="kpi-icon {{ $metrics['pending_checklist'] > 0 ? 'orange' : 'green' }}">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $metrics['pending_checklist'] }}</div>
        <div class="kpi-label">Pending Tasks</div>
        @if($metrics['pending_checklist'] > 0)
        <div style="margin-top: 12px;">
            <a href="{{ route('mother.checklist') }}" style="font-size: 12px; color: var(--warning); text-decoration: none;">
                <i class="fas fa-arrow-right"></i> Complete tasks
            </a>
        </div>
        @endif
    </div>

    @if($metrics['latest_weight'])
    <div class="kpi-card animate__animated animate__fadeInUp" style="animation-delay: 250ms;">
        <div class="kpi-header">
            <div class="kpi-icon pink">
                <i class="fas fa-weight"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $metrics['latest_weight']->weight_kg }} <span style="font-size: 16px; font-weight: 500;">kg</span></div>
        <div class="kpi-label">Latest Weight</div>
        <div style="margin-top: 12px; font-size: 12px; color: var(--gray);">
            <i class="far fa-clock"></i> {{ $metrics['latest_weight']->recorded_date->diffForHumans() }}
        </div>
    </div>
    @endif

    @if($metrics['latest_bp'])
    <div class="kpi-card animate__animated animate__fadeInUp" style="animation-delay: 300ms;">
        <div class="kpi-header">
            <div class="kpi-icon {{ $metrics['latest_bp']->severity_level === 'normal' ? 'green' : ($metrics['latest_bp']->severity_level === 'critical' ? 'red' : 'orange') }}">
                <i class="fas fa-heart-pulse"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $metrics['latest_bp']->systolic }}/{{ $metrics['latest_bp']->diastolic }}</div>
        <div class="kpi-label">Blood Pressure</div>
        <div style="margin-top: 12px;">
            <span class="badge badge-{{ $metrics['latest_bp']->severity_level === 'normal' ? 'green' : ($metrics['latest_bp']->severity_level === 'critical' ? 'red' : 'orange') }}">
                {{ ucfirst($metrics['latest_bp']->severity_level) }}
            </span>
        </div>
    </div>
    @endif
</div>

{{-- Main Grid --}}
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px;">
    {{-- Left Column --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        {{-- Weekly Tip Card --}}
        <div class="card animate__animated animate__fadeIn" style="animation-delay: 100ms; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); border: 1px solid rgba(236, 72, 153, 0.2);">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb" style="color: #f59e0b;"></i>
                    This Week's Tip
                </h3>
                @if($mother->weeks_pregnant)
                <span class="badge badge-pink">Week {{ $mother->weeks_pregnant }}</span>
                @endif
            </div>
            <div>
                <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 8px; color: var(--primary-dark);">
                    {{ $weeklyTip['title'] }}
                </h4>
                <p style="color: var(--gray); line-height: 1.7;">
                    {{ $weeklyTip['content'] }}
                </p>
            </div>
        </div>

        {{-- Pregnancy Timeline --}}
        @if($mother->status === 'pregnant')
        <div class="card animate__animated animate__fadeIn" style="animation-delay: 150ms;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-stream"></i>
                    Pregnancy Journey
                </h3>
                <div style="font-size: 14px; color: var(--gray);">
                    {{ $metrics['progress_percentage'] }}% Complete
                </div>
            </div>
            <div style="position: relative; padding-left: 32px;">
                <div style="position: absolute; left: 8px; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, var(--primary) 0%, var(--primary-light) 100%);"></div>
                
                @foreach($timeline as $milestone)
                <div style="position: relative; margin-bottom: 24px; {{ $milestone['status'] === 'upcoming' ? 'opacity: 0.5;' : '' }}">
                    <div style="position: absolute; left: -28px; width: 16px; height: 16px; border-radius: 50%; background: {{ $milestone['status'] === 'completed' ? 'var(--success)' : ($milestone['status'] === 'current' ? 'var(--primary)' : 'var(--gray)') }}; border: 3px solid white; box-shadow: 0 0 0 2px {{ $milestone['status'] === 'completed' ? 'var(--success)' : ($milestone['status'] === 'current' ? 'var(--primary)' : 'var(--gray)') }};"></div>
                    <div style="font-size: 12px; color: var(--gray); margin-bottom: 4px;">Week {{ $milestone['week'] }}</div>
                    <div style="font-weight: 500; color: var(--dark);">{{ $milestone['title'] }}</div>
                    @if($milestone['status'] === 'current')
                    <div style="margin-top: 4px; font-size: 12px; color: var(--primary);">
                        <i class="fas fa-map-marker-alt"></i> You are here
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Health Charts --}}
        <div class="card animate__animated animate__fadeIn" style="animation-delay: 200ms;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Health Trends
                </h3>
                <a href="{{ route('mother.health-data') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                    <i class="fas fa-plus"></i> Add Data
                </a>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                @if(count($weightChartData['data']) > 0)
                <div>
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px; color: var(--gray);">Weight (kg)</h4>
                    <div style="height: 200px;">
                        <canvas id="weightChart"></canvas>
                    </div>
                </div>
                @endif
                @if(count($kickChartData['data']) > 0)
                <div>
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px; color: var(--gray);">Baby Kicks</h4>
                    <div style="height: 200px;">
                        <canvas id="kickChart"></canvas>
                    </div>
                </div>
                @endif
            </div>
            @if(count($weightChartData['data']) === 0 && count($kickChartData['data']) === 0)
            <div style="text-align: center; padding: 40px; color: var(--gray);">
                <i class="fas fa-chart-area" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p>No health data recorded yet. Start tracking your health!</p>
                <a href="{{ route('mother.health-data') }}" class="btn btn-primary" style="margin-top: 16px;">
                    <i class="fas fa-plus"></i> Add First Entry
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Right Column --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        {{-- Quick Actions --}}
        <div class="card animate__animated animate__fadeIn" style="animation-delay: 100ms;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h3>
            </div>
            <div style="display: grid; gap: 12px;">
                <a href="{{ route('mother.health-data') }}#weight" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-weight" style="color: var(--primary);"></i>
                    Log Weight
                </a>
                <a href="{{ route('mother.health-data') }}#bp" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-heart-pulse" style="color: var(--danger);"></i>
                    Log Blood Pressure
                </a>
                <a href="{{ route('mother.health-data') }}#kicks" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-baby" style="color: var(--secondary);"></i>
                    Count Baby Kicks
                </a>
                <a href="{{ route('mother.daily-log') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-clipboard-list" style="color: var(--warning);"></i>
                    Daily Check-in
                </a>
            </div>
        </div>

        {{-- Upcoming Appointments --}}
        <div class="card animate__animated animate__fadeIn" style="animation-delay: 150ms;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar"></i>
                    Upcoming
                </h3>
                <a href="{{ route('mother.appointments') }}" style="font-size: 12px; color: var(--primary); text-decoration: none;">View all</a>
            </div>
            @if($mother->appointments->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($mother->appointments->take(3) as $appointment)
                <div style="padding: 16px; background: var(--light); border-radius: 12px; border-left: 4px solid var(--primary);">
                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $appointment->title }}</div>
                    <div style="font-size: 13px; color: var(--gray); margin-bottom: 8px;">
                        <i class="far fa-clock"></i> {{ $appointment->appointment_date->format('M d, Y g:i A') }}
                    </div>
                    @if($appointment->clinic_name)
                    <div style="font-size: 12px; color: var(--gray);">
                        <i class="fas fa-hospital"></i> {{ $appointment->clinic_name }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 24px; color: var(--gray);">
                <i class="far fa-calendar" style="font-size: 32px; margin-bottom: 12px; opacity: 0.5;"></i>
                <p style="font-size: 14px;">No upcoming appointments</p>
                <a href="{{ route('mother.appointments') }}" class="btn btn-primary" style="margin-top: 12px; padding: 8px 16px; font-size: 12px;">
                    <i class="fas fa-plus"></i> Schedule
                </a>
            </div>
            @endif
        </div>

        {{-- Recent Alerts --}}
        <div class="card animate__animated animate__fadeIn" style="animation-delay: 200ms;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell"></i>
                    Recent Alerts
                </h3>
                <a href="{{ route('mother.alerts') }}" style="font-size: 12px; color: var(--primary); text-decoration: none;">View all</a>
            </div>
            @if($mother->healthAlerts->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($mother->healthAlerts->take(3) as $alert)
                <div style="padding: 16px; background: {{ $alert->severity === 'critical' ? '#fee2e2' : ($alert->severity === 'high' ? '#ffedd5' : '#dbeafe') }}; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <i class="fas {{ $alert->icon }}" style="color: {{ $alert->severity === 'critical' ? '#dc2626' : ($alert->severity === 'high' ? '#ea580c' : '#2563eb') }};"></i>
                        <span style="font-weight: 600; font-size: 14px;">{{ $alert->alert_type_label }}</span>
                    </div>
                    <p style="font-size: 13px; color: var(--gray); margin-bottom: 8px; line-height: 1.5;">
                        {{ Str::limit($alert->message, 80) }}
                    </p>
                    <div style="display: flex; gap: 8px;">
                        <span class="badge badge-{{ $alert->severity === 'critical' ? 'red' : ($alert->severity === 'high' ? 'orange' : 'blue') }}">
                            {{ ucfirst($alert->severity) }}
                        </span>
                        @if(!$alert->is_read)
                        <span class="badge badge-gray">Unread</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 24px; color: var(--gray);">
                <i class="fas fa-check-circle" style="font-size: 32px; margin-bottom: 12px; color: var(--success);"></i>
                <p style="font-size: 14px;">No active health alerts</p>
            </div>
            @endif
        </div>

        {{-- Daily Check-in --}}
        <div class="card animate__animated animate__fadeIn" style="animation-delay: 250ms;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-smile"></i>
                    Today's Check-in
                </h3>
            </div>
            @if($todayLog)
            <div style="text-align: center; padding: 20px;">
                <i class="fas {{ $todayLog->mood_icon }}" style="font-size: 48px; color: {{ $todayLog->mood_color }}; margin-bottom: 12px;"></i>
                <div style="font-size: 18px; font-weight: 600; margin-bottom: 4px;">
                    Feeling {{ $todayLog->mood_label }}
                </div>
                @if($todayLog->water_intake_glasses)
                <div style="font-size: 13px; color: var(--gray); margin-top: 8px;">
                    <i class="fas fa-glass-water"></i> {{ $todayLog->water_intake_glasses }} glasses of water
                </div>
                @endif
                @if($todayLog->sleep_hours)
                <div style="font-size: 13px; color: var(--gray);">
                    <i class="fas fa-bed"></i> {{ $todayLog->sleep_hours }} hours sleep
                </div>
                @endif
            </div>
            @else
            <div style="text-align: center; padding: 24px; color: var(--gray);">
                <i class="fas fa-clipboard-list" style="font-size: 32px; margin-bottom: 12px; opacity: 0.5;"></i>
                <p style="font-size: 14px; margin-bottom: 16px;">Haven't checked in today</p>
                <a href="{{ route('mother.daily-log') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">
                    <i class="fas fa-plus"></i> Check-in Now
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Weight Chart
    @if(count($weightChartData['data']) > 0)
    const weightCtx = document.getElementById('weightChart').getContext('2d');
    new Chart(weightCtx, {
        type: 'line',
        data: {
            labels: @json($weightChartData['labels']),
            datasets: [{
                label: 'Weight (kg)',
                data: @json($weightChartData['data']),
                borderColor: '#ec4899',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#ec4899',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    @endif

    // Kick Count Chart
    @if(count($kickChartData['data']) > 0)
    const kickCtx = document.getElementById('kickChart').getContext('2d');
    new Chart(kickCtx, {
        type: 'bar',
        data: {
            labels: @json($kickChartData['labels']),
            datasets: [{
                label: 'Kicks',
                data: @json($kickChartData['data']),
                backgroundColor: '#3b82f6',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    @endif
</script>
@endpush
