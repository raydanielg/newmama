@extends('layouts.mother')

@section('title', 'Health Data - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-heartbeat"></i>
        Health Tracking
    </h1>
</div>

{{-- Quick Stats --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon pink">
                <i class="fas fa-weight"></i>
            </div>
        </div>
        <div class="kpi-value">{{ count($weightLogs) }}</div>
        <div class="kpi-label">Weight Records</div>
        @if($stats['weight_change'] !== null)
        <div style="margin-top: 12px; font-size: 12px; color: {{ $stats['weight_change'] >= 0 ? 'var(--success)' : 'var(--primary)' }};">
            <i class="fas fa-arrow-{{ $stats['weight_change'] >= 0 ? 'up' : 'down' }}"></i>
            {{ abs($stats['weight_change']) }} kg change
        </div>
        @endif
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon blue">
                <i class="fas fa-heart-pulse"></i>
            </div>
        </div>
        <div class="kpi-value">{{ count($bpLogs) }}</div>
        <div class="kpi-label">BP Readings</div>
        @if($stats['avg_bp'])
        <div style="margin-top: 12px; font-size: 12px; color: var(--secondary);">
            Avg: {{ $stats['avg_bp']['systolic'] }}/{{ $stats['avg_bp']['diastolic'] }}
        </div>
        @endif
    </div>
    
    <div class="kpi-card">
        <div class="kpi-header">
            <div class="kpi-icon green">
                <i class="fas fa-baby"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $stats['total_kicks_recorded'] }}</div>
        <div class="kpi-label">Total Kicks Recorded</div>
        <div style="margin-top: 12px; font-size: 12px; color: var(--gray);">
            {{ count($kickCounts) }} sessions
        </div>
    </div>
</div>

{{-- Weight Tracking Section --}}
<div id="weight" class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-weight"></i>
            Weight Tracking
        </h3>
        <button class="btn btn-primary" onclick="showWeightModal()">
            <i class="fas fa-plus"></i> Add Weight
        </button>
    </div>
    
    @if(count($weightLogs) > 0)
    <div style="margin-bottom: 20px;">
        <div style="height: 300px;">
            <canvas id="weightChart"></canvas>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Weight</th>
                    <th>Week</th>
                    <th>Change</th>
                </tr>
            </thead>
            <tbody>
                @foreach($weightLogs->take(10) as $log)
                <tr>
                    <td>{{ $log->recorded_date->format('M d, Y') }}</td>
                    <td><strong>{{ $log->weight_kg }} kg</strong></td>
                    <td>{{ $log->weeks_pregnant ? 'Week ' . $log->weeks_pregnant : '—' }}</td>
                    <td>
                        @if($log->weight_gain_from_start !== null)
                            <span style="color: {{ $log->weight_gain_from_start >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $log->weight_gain_from_start >= 0 ? '+' : '' }}{{ $log->weight_gain_from_start }} kg
                            </span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 60px 20px; color: var(--gray);">
        <i class="fas fa-weight" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
        <p style="margin-bottom: 20px;">Start tracking your weight during pregnancy</p>
        <button class="btn btn-primary" onclick="showWeightModal()">
            <i class="fas fa-plus"></i> Add First Entry
        </button>
    </div>
    @endif
</div>

{{-- Blood Pressure Section --}}
<div id="bp" class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-heart-pulse"></i>
            Blood Pressure
        </h3>
        <button class="btn btn-primary" onclick="showBpModal()">
            <i class="fas fa-plus"></i> Add Reading
        </button>
    </div>
    
    @if(count($bpLogs) > 0)
    <div style="margin-bottom: 20px;">
        <div style="height: 300px;">
            <canvas id="bpChart"></canvas>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>BP Reading</th>
                    <th>Heart Rate</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bpLogs->take(10) as $log)
                <tr>
                    <td>{{ $log->recorded_at->format('M d, Y g:i A') }}</td>
                    <td>
                        <strong>{{ $log->systolic }}/{{ $log->diastolic }}</strong> mmHg
                        @if($log->map)
                        <div style="font-size: 12px; color: var(--gray);">MAP: {{ $log->map }}</div>
                        @endif
                    </td>
                    <td>{{ $log->heart_rate ? $log->heart_rate . ' bpm' : '—' }}</td>
                    <td>
                        <span class="badge {{ $log->severity_badge_class }}">
                            {{ ucfirst($log->severity_level) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 60px 20px; color: var(--gray);">
        <i class="fas fa-heart-pulse" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
        <p style="margin-bottom: 20px;">Monitor your blood pressure regularly</p>
        <button class="btn btn-primary" onclick="showBpModal()">
            <i class="fas fa-plus"></i> Add First Reading
        </button>
    </div>
    @endif
</div>

{{-- Kick Count Section --}}
<div id="kicks" class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-baby"></i>
            Baby Kick Counter
        </h3>
        <button class="btn btn-primary" onclick="showKickModal()">
            <i class="fas fa-plus"></i> Add Session
        </button>
    </div>
    
    @if(count($kickCounts) > 0)
    <div style="margin-bottom: 20px;">
        <div style="height: 300px;">
            <canvas id="kickChart"></canvas>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Kicks</th>
                    <th>Duration</th>
                    <th>Per Hour</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kickCounts->take(10) as $log)
                <tr>
                    <td>{{ $log->recorded_date->format('M d, Y') }}</td>
                    <td><strong>{{ $log->kick_count }}</strong></td>
                    <td>{{ $log->duration_minutes }} min</td>
                    <td>{{ $log->kicks_per_hour }}</td>
                    <td>
                        <span class="badge {{ $log->status_badge_class }}">
                            {{ $log->status_label }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 60px 20px; color: var(--gray);">
        <i class="fas fa-hand-sparkles" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
        <p style="margin-bottom: 20px;">Track your baby's movements to ensure they're healthy</p>
        <div style="background: #dbeafe; padding: 16px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
            <i class="fas fa-info-circle" style="color: var(--secondary);"></i>
            You should feel about <strong>10 kicks every 2 hours</strong> when the baby is active
        </div>
        <button class="btn btn-primary" onclick="showKickModal()">
            <i class="fas fa-plus"></i> Add First Session
        </button>
    </div>
    @endif
</div>

{{-- Weight Modal --}}
<div id="weightModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 32px; width: 90%; max-width: 400px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600;">Add Weight Entry</h3>
            <button onclick="hideModal('weightModal')" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('mother.health-data.weight') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Weight (kg) *</label>
                <input type="number" name="weight_kg" class="form-input" step="0.1" min="30" max="200" required placeholder="e.g., 65.5">
            </div>
            <div class="form-group">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" class="form-input" rows="2" placeholder="e.g., Morning weight, after breakfast..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-save"></i> Save Weight
            </button>
        </form>
    </div>
</div>

{{-- BP Modal --}}
<div id="bpModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 32px; width: 90%; max-width: 400px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600;">Add BP Reading</h3>
            <button onclick="hideModal('bpModal')" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('mother.health-data.bp') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Systolic *</label>
                    <input type="number" name="systolic" class="form-input" min="70" max="200" required placeholder="120">
                </div>
                <div class="form-group">
                    <label class="form-label">Diastolic *</label>
                    <input type="number" name="diastolic" class="form-input" min="40" max="130" required placeholder="80">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Heart Rate (bpm)</label>
                <input type="number" name="heart_rate" class="form-input" min="40" max="150" placeholder="72">
            </div>
            <div class="form-group">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" class="form-input" rows="2" placeholder="e.g., Resting, after walking..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-save"></i> Save Reading
            </button>
        </form>
    </div>
</div>

{{-- Kick Modal --}}
<div id="kickModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 32px; width: 90%; max-width: 400px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 600;">Record Kick Count</h3>
            <button onclick="hideModal('kickModal')" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('mother.health-data.kicks') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Kicks Counted *</label>
                    <input type="number" name="kick_count" class="form-input" min="0" max="100" required placeholder="12">
                </div>
                <div class="form-group">
                    <label class="form-label">Duration (min) *</label>
                    <input type="number" name="duration_minutes" class="form-input" min="1" max="180" required placeholder="60">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" class="form-input" rows="2" placeholder="e.g., Active morning session..."></textarea>
            </div>
            <div style="background: #dbeafe; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                <i class="fas fa-lightbulb" style="color: var(--secondary);"></i>
                Tip: Count kicks when baby is most active, usually after meals
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-save"></i> Save Session
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showWeightModal() { document.getElementById('weightModal').style.display = 'flex'; }
    function showBpModal() { document.getElementById('bpModal').style.display = 'flex'; }
    function showKickModal() { document.getElementById('kickModal').style.display = 'flex'; }
    function hideModal(id) { document.getElementById(id).style.display = 'none'; }
    
    // Close modals when clicking outside
    ['weightModal', 'bpModal', 'kickModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) hideModal(id);
        });
    });

    // Weight Chart
    @if(count($weightLogs) > 0)
    const weightCtx = document.getElementById('weightChart').getContext('2d');
    new Chart(weightCtx, {
        type: 'line',
        data: {
            labels: @json($weightLogs->sortBy('recorded_date')->map(fn($l) => $l->recorded_date->format('M d'))->values()),
            datasets: [{
                label: 'Weight (kg)',
                data: @json($weightLogs->sortBy('recorded_date')->map(fn($l) => $l->weight_kg)->values()),
                borderColor: '#ec4899',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#ec4899',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: false, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
    @endif

    // BP Chart
    @if(count($bpLogs) > 0)
    const bpCtx = document.getElementById('bpChart').getContext('2d');
    new Chart(bpCtx, {
        type: 'line',
        data: {
            labels: @json($bpLogs->sortBy('recorded_at')->map(fn($l) => $l->recorded_at->format('M d H:i'))->values()),
            datasets: [
                {
                    label: 'Systolic',
                    data: @json($bpLogs->sortBy('recorded_at')->map(fn($l) => $l->systolic)->values()),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                },
                {
                    label: 'Diastolic',
                    data: @json($bpLogs->sortBy('recorded_at')->map(fn($l) => $l->diastolic)->values()),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: false, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
    @endif

    // Kick Chart
    @if(count($kickCounts) > 0)
    const kickCtx = document.getElementById('kickChart').getContext('2d');
    new Chart(kickCtx, {
        type: 'bar',
        data: {
            labels: @json($kickCounts->sortBy('recorded_date')->map(fn($l) => $l->recorded_date->format('M d'))->values()),
            datasets: [{
                label: 'Kicks',
                data: @json($kickCounts->sortBy('recorded_date')->map(fn($l) => $l->kick_count)->values()),
                backgroundColor: '#3b82f6',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
    @endif
</script>
@endpush
