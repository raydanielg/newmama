@extends('layouts.mother')

@section('title', 'Daily Log - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-clipboard-list"></i>
        Daily Check-in
    </h1>
    <span style="color: var(--gray); font-size: 14px;">
        {{ now()->format('l, F j, Y') }}
    </span>
</div>

{{-- Today's Check-in Form --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar-day"></i>
            @if($todayLog)
            Today's Entry
            @else
            How are you feeling today?
            @endif
        </h3>
    </div>
    
    <form action="{{ route('mother.daily-log.store') }}" method="POST">
        @csrf
        
        {{-- Mood Selection --}}
        <div style="margin-bottom: 24px;">
            <label class="form-label">Your Mood Today *</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px;">
                @foreach(['great', 'good', 'okay', 'tired', 'sad', 'anxious'] as $mood)
                <label style="cursor: pointer; text-align: center; padding: 16px; border-radius: 12px; border: 2px solid {{ ($todayLog?->mood ?? old('mood')) === $mood ? 'var(--primary)' : '#e5e7eb' }}; background: {{ ($todayLog?->mood ?? old('mood')) === $mood ? 'var(--primary-light)' : 'white' }}; transition: all 0.2s ease;">
                    <input type="radio" name="mood" value="{{ $mood }}" style="display: none;" {{ ($todayLog?->mood ?? old('mood')) === $mood ? 'checked' : '' }} required>
                    <i class="fas fa-face-{{ $mood === 'great' ? 'laugh-beam' : ($mood === 'good' ? 'smile' : ($mood === 'okay' ? 'meh' : ($mood === 'tired' ? 'tired' : ($mood === 'sad' ? 'frown' : 'grimace')))) }}" style="font-size: 32px; color: {{ ($todayLog?->mood ?? old('mood')) === $mood ? 'var(--primary)' : '#9ca3af' }}; margin-bottom: 8px; display: block;"></i>
                    <span style="font-size: 14px; font-weight: 500; text-transform: capitalize; color: {{ ($todayLog?->mood ?? old('mood')) === $mood ? 'var(--primary-dark)' : 'var(--gray)' }};">{{ $mood }}</span>
                </label>
                @endforeach
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-bed" style="color: var(--secondary);"></i> Sleep Hours
                </label>
                <input type="number" name="sleep_hours" class="form-input" step="0.5" min="0" max="24" 
                    value="{{ $todayLog?->sleep_hours ?? old('sleep_hours') }}" placeholder="e.g., 7.5">
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-glass-water" style="color: var(--info);"></i> Water Intake (glasses)
                </label>
                <input type="number" name="water_intake_glasses" class="form-input" min="0" max="50"
                    value="{{ $todayLog?->water_intake_glasses ?? old('water_intake_glasses') }}" placeholder="e.g., 8">
            </div>
        </div>
        
        {{-- Symptoms --}}
        <div style="margin-bottom: 24px;">
            <label class="form-label">
                <i class="fas fa-heart-pulse" style="color: var(--danger);"></i> Symptoms Today
            </label>
            @php
            $commonSymptoms = [
                'nausea' => 'Nausea / Morning Sickness',
                'vomiting' => 'Vomiting',
                'headache' => 'Headache',
                'back_pain' => 'Back Pain',
                'cramps' => 'Cramps',
                'swelling' => 'Swelling',
                'heartburn' => 'Heartburn',
                'constipation' => 'Constipation',
                'fatigue' => 'Fatigue',
                'insomnia' => 'Insomnia',
                'dizziness' => 'Dizziness',
                'shortness_of_breath' => 'Shortness of Breath',
                'breast_tenderness' => 'Breast Tenderness',
                'frequent_urination' => 'Frequent Urination',
                'food_cravings' => 'Food Cravings',
                'mood_swings' => 'Mood Swings',
            ];
            $selectedSymptoms = $todayLog?->symptoms ?? old('symptoms', []);
            @endphp
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px;">
                @foreach($commonSymptoms as $key => $label)
                <label style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: var(--light); border-radius: 8px; cursor: pointer;">
                    <input type="checkbox" name="symptoms[]" value="{{ $key }}" {{ in_array($key, $selectedSymptoms) ? 'checked' : '' }}>
                    <span style="font-size: 14px;">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-input" rows="3" placeholder="How are you feeling? Any thoughts or concerns...">{{ $todayLog?->notes ?? old('notes') }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-save"></i> {{ $todayLog ? 'Update Today\'s Entry' : 'Save Today\'s Entry' }}
        </button>
    </form>
</div>

{{-- Past Logs --}}
@if(count($logs) > 0)
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history"></i>
            Past Entries
        </h3>
    </div>
    
    <div style="display: flex; flex-direction: column; gap: 16px;">
        @foreach($logs as $log)
        <div style="padding: 20px; background: var(--light); border-radius: 12px;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px; flex-wrap: wrap;">
                <i class="fas {{ $log->mood_icon }}" style="font-size: 28px; color: {{ $log->mood_color }};"></i>
                <div>
                    <div style="font-weight: 600;">{{ $log->log_date->format('l, M d, Y') }}</div>
                    <div style="font-size: 14px; color: var(--gray);">Feeling {{ $log->mood_label }}</div>
                </div>
                <span style="margin-left: auto; font-size: 12px; color: var(--gray);">
                    {{ $log->created_at->diffForHumans() }}
                </span>
            </div>
            
            <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 12px;">
                @if($log->sleep_hours)
                <span style="font-size: 13px; color: var(--secondary);">
                    <i class="fas fa-bed"></i> {{ $log->sleep_hours }} hours sleep
                </span>
                @endif
                @if($log->water_intake_glasses)
                <span style="font-size: 13px; color: var(--info);">
                    <i class="fas fa-glass-water"></i> {{ $log->water_intake_glasses }} glasses water
                </span>
                @endif
            </div>
            
            @if(count($log->symptoms ?? []) > 0)
            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px;">
                @foreach($log->symptoms_labels as $symptomLabel)
                <span style="font-size: 11px; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 10px;">{{ $symptomLabel }}</span>
                @endforeach
            </div>
            @endif
            
            @if($log->notes)
            <div style="font-size: 14px; color: var(--gray); padding: 12px; background: white; border-radius: 8px;">
                {{ $log->notes }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    
    <div style="margin-top: 24px;">
        {{ $logs->links() }}
    </div>
</div>
@endif
@endsection
