@extends('layouts.mother')

@section('title', 'Dashboard - MamaCare')

@section('content')
<div class="animate__animated animate__fadeIn">
    {{-- Critical Alerts --}}
    @if($metrics['critical_alerts'] > 0)
    <div class="mb-8 p-6 rounded-3xl bg-rose-600 text-white shadow-xl shadow-rose-200 flex items-center gap-6 animate__animated animate__pulse animate__infinite">
        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0 backdrop-blur-md">
            <i class="fas fa-exclamation-triangle text-2xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-black tracking-tight">{{ $metrics['critical_alerts'] }} Critical Health Alert{{ $metrics['critical_alerts'] > 1 ? 's' : '' }}</h3>
            <p class="text-rose-100 text-sm font-medium">Please review immediately and contact your healthcare provider.</p>
        </div>
        <a href="{{ route('mother.alerts') }}" class="px-6 py-3 bg-white text-rose-600 rounded-xl text-sm font-black hover:bg-rose-50 transition-colors shadow-lg">View Alerts</a>
    </div>
    @endif

    {{-- Status Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Pregnancy Status --}}
        @if($mother->status === 'pregnant')
        <div class="glass-card flex flex-col items-center justify-center text-center p-8 group hover:border-primary/30 transition-all">
            <div class="w-16 h-16 bg-indigo-100 text-primary rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-baby text-2xl"></i>
            </div>
            <h4 class="text-[32px] font-black text-slate-900 leading-none mb-1">{{ $metrics['weeks_pregnant'] ?? 'N/A' }}</h4>
            <p class="text-slate-500 text-xs font-extrabold uppercase tracking-widest">Weeks Pregnant</p>
            <div class="mt-4 px-3 py-1 bg-indigo-50 text-primary text-[10px] font-black rounded-full uppercase">Trimester {{ $metrics['trimester'] }}</div>
        </div>

        <div class="glass-card flex flex-col items-center justify-center text-center p-8 group hover:border-emerald-300/30 transition-all">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-day text-2xl"></i>
            </div>
            <h4 class="text-[32px] font-black text-slate-900 leading-none mb-1">
                {{ $metrics['days_until_edd'] > 0 ? $metrics['days_until_edd'] : ($metrics['days_until_edd'] === 0 ? 'Due Today' : 'Overdue') }}
            </h4>
            <p class="text-slate-500 text-xs font-extrabold uppercase tracking-widest">Days to Due Date</p>
            <div class="mt-4 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full uppercase">{{ $mother->edd_date ? $mother->edd_date->format('M d, Y') : 'N/A' }}</div>
        </div>
        @endif

        {{-- Appointment Status --}}
        <div class="glass-card flex flex-col items-center justify-center text-center p-8 group hover:border-amber-300/30 transition-all">
            <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <h4 class="text-[32px] font-black text-slate-900 leading-none mb-1">{{ $metrics['upcoming_appointments'] }}</h4>
            <p class="text-slate-500 text-xs font-extrabold uppercase tracking-widest">Appointments</p>
            <a href="{{ route('mother.appointments') }}" class="mt-4 text-amber-600 text-[10px] font-black uppercase underline underline-offset-4 tracking-tighter">View Schedule</a>
        </div>

        {{-- Alerts Status --}}
        <div class="glass-card flex flex-col items-center justify-center text-center p-8 group hover:border-rose-300/30 transition-all">
            <div class="w-16 h-16 {{ $metrics['unread_alerts'] > 0 ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-400' }} rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-bell text-2xl"></i>
            </div>
            <h4 class="text-[32px] font-black text-slate-900 leading-none mb-1">{{ $metrics['unread_alerts'] }}</h4>
            <p class="text-slate-500 text-xs font-extrabold uppercase tracking-widest">Unread Alerts</p>
            <div class="mt-4 px-3 py-1 {{ $metrics['unread_alerts'] > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-400' }} text-[10px] font-black rounded-full uppercase">
                {{ $metrics['unread_alerts'] > 0 ? 'Review Now' : 'All Caught Up' }}
            </div>
        </div>
    </div>

    {{-- Main Activity Area --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Health Progress --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Weekly Highlight --}}
            <div class="relative overflow-hidden bg-slate-900 rounded-[32px] p-10 text-white shadow-2xl">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-4 py-1.5 bg-primary rounded-full text-[10px] font-black uppercase tracking-widest">Week {{ $mother->weeks_pregnant ?? 'N/A' }} Tip</span>
                        <div class="h-px flex-1 bg-white/10"></div>
                    </div>
                    <h2 class="text-3xl font-black mb-4 leading-tight">{{ $weeklyTip['title'] }}</h2>
                    <p class="text-slate-400 leading-relaxed font-medium text-lg">{{ $weeklyTip['content'] }}</p>
                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('mother.education') }}" class="btn-modern bg-white text-slate-900 hover:bg-slate-100">
                            <i class="fas fa-book-open"></i> Learn More
                        </a>
                        <a href="{{ route('mother.daily-log') }}" class="btn-modern bg-white/10 text-white hover:bg-white/20 backdrop-blur-md">
                            <i class="fas fa-plus"></i> Daily Check-in
                        </a>
                    </div>
                </div>
            </div>

            {{-- Charts Area --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glass-card">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 leading-none">Weight Track</h3>
                            <p class="text-slate-500 text-[10px] font-extrabold uppercase mt-1">Latest: {{ $metrics['latest_weight'] ? $metrics['latest_weight']->weight_kg . ' kg' : 'No data' }}</p>
                        </div>
                        <i class="fas fa-weight-scale text-primary/20 text-2xl"></i>
                    </div>
                    <div class="h-[200px]">
                        <canvas id="weightChart"></canvas>
                    </div>
                </div>
                <div class="glass-card">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 leading-none">Baby Kicks</h3>
                            <p class="text-slate-500 text-[10px] font-extrabold uppercase mt-1">Daily goal: 10 kicks</p>
                        </div>
                        <i class="fas fa-footprints text-primary/20 text-2xl"></i>
                    </div>
                    <div class="h-[200px]">
                        <canvas id="kickChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Widgets --}}
        <div class="space-y-8">
            {{-- Pregnancy Journey --}}
            <div class="glass-card p-0 overflow-hidden">
                <div class="p-8 border-b border-slate-100">
                    <h3 class="text-lg font-black text-slate-900">Your Journey</h3>
                    <div class="mt-4 w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: {{ $metrics['progress_percentage'] }}%"></div>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-2">{{ $metrics['progress_percentage'] }}% Complete</p>
                </div>
                <div class="p-8 space-y-6">
                    @foreach($timeline as $milestone)
                    <div class="flex items-start gap-4 {{ $milestone['status'] === 'upcoming' ? 'opacity-40' : '' }}">
                        <div class="relative flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full border-4 {{ $milestone['status'] === 'completed' ? 'bg-emerald-500 border-emerald-100' : ($milestone['status'] === 'current' ? 'bg-primary border-indigo-100' : 'bg-slate-200 border-slate-50') }} relative z-10"></div>
                            @if(!$loop->last)
                            <div class="w-px h-12 bg-slate-100 -mb-6"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase leading-none mb-1">Week {{ $milestone['week'] }}</p>
                            <h4 class="text-sm font-black {{ $milestone['status'] === 'current' ? 'text-primary' : 'text-slate-700' }}">{{ $milestone['title'] }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick Daily Status --}}
            <div class="glass-card bg-indigo-600 text-white border-none shadow-indigo-200">
                <h3 class="text-lg font-black mb-6">Today's Vitals</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                        <p class="text-[10px] font-bold text-indigo-100 uppercase mb-1">Blood Pressure</p>
                        <p class="text-lg font-black">{{ $metrics['latest_bp'] ? $metrics['latest_bp']->systolic . '/' . $metrics['latest_bp']->diastolic : '--/--' }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                        <p class="text-[10px] font-bold text-indigo-100 uppercase mb-1">Mood</p>
                        <p class="text-lg font-black">{{ $todayLog ? $todayLog->mood_label : 'No data' }}</p>
                    </div>
                </div>
                <a href="{{ route('mother.health-data') }}" class="mt-6 w-full py-3 bg-white text-indigo-600 rounded-xl text-xs font-black flex items-center justify-center gap-2">
                    Update Vitals <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Config common chart options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }, ticks: { font: { size: 10, weight: 'bold' } } },
            x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
        }
    };

    // Weight Chart
    @if(count($weightChartData['data']) > 0)
    new Chart(document.getElementById('weightChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($weightChartData['labels']),
            datasets: [{
                data: @json($weightChartData['data']),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 3
            }]
        },
        options: chartOptions
    });
    @endif

    // Kick Chart
    @if(count($kickChartData['data']) > 0)
    new Chart(document.getElementById('kickChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($kickChartData['labels']),
            datasets: [{
                data: @json($kickChartData['data']),
                backgroundColor: '#10b981',
                borderRadius: 8,
                barThickness: 12
            }]
        },
        options: chartOptions
    });
    @endif
</script>
@endpush
