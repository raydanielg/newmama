@extends('layouts.mother')

@section('title', 'Checklist - MamaCare')

@section('content')
<div class="header">
    <h1 class="page-title">
        <i class="fas fa-tasks"></i>
        Pregnancy Checklist
    </h1>
</div>

{{-- Progress Overview --}}
<div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div>
            <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 8px;">Your Progress</h3>
            <p style="opacity: 0.9;">{{ $progress['completed'] }} of {{ $progress['total'] }} tasks completed</p>
        </div>
        <div style="text-align: center;">
            <div style="width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; position: relative;">
                <svg width="120" height="120" style="position: absolute; transform: rotate(-90deg);">
                    <circle cx="60" cy="60" r="54" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="8"/>
                    <circle cx="60" cy="60" r="54" fill="none" stroke="white" stroke-width="8" 
                        stroke-dasharray="339.292" 
                        stroke-dashoffset="{{ 339.292 - (339.292 * $progress['percentage'] / 100) }}"
                        style="transition: stroke-dashoffset 0.5s ease;"/>
                </svg>
                <div style="font-size: 28px; font-weight: 700;">{{ round($progress['percentage']) }}%</div>
            </div>
        </div>
    </div>
</div>

{{-- Checklist by Category --}}
@if(count($items) > 0)
    @foreach($items as $category => $categoryItems)
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas {{ $categoryItems->first()->category_icon }}"></i>
                {{ $categoryItems->first()->category_label }}
            </h3>
            <span class="badge badge-pink">
                {{ $categoryItems->where('is_completed', true)->count() }}/{{ $categoryItems->count() }} Done
            </span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($categoryItems as $item)
            <div style="display: flex; align-items: center; gap: 16px; padding: 16px; background: {{ $item->is_completed ? '#f0fdf4' : 'var(--light)' }}; border-radius: 12px; border: 2px solid {{ $item->is_completed ? '#86efac' : 'transparent' }}; transition: all 0.2s ease;">
                <form action="{{ route('mother.checklist.toggle', $item) }}" method="POST" style="flex-shrink: 0;">
                    @csrf
                    <button type="submit" style="width: 28px; height: 28px; border-radius: 50%; border: 2px solid {{ $item->is_completed ? '#22c55e' : 'var(--gray)' }}; background: {{ $item->is_completed ? '#22c55e' : 'transparent' }}; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                        @if($item->is_completed)
                        <i class="fas fa-check" style="color: white; font-size: 12px;"></i>
                        @endif
                    </button>
                </form>
                
                <div style="flex: 1;">
                    <div style="font-weight: {{ $item->is_completed ? '400' : '600' }}; text-decoration: {{ $item->is_completed ? 'line-through' : 'none' }}; color: {{ $item->is_completed ? 'var(--gray)' : 'var(--dark)' }}; margin-bottom: 4px;">
                        {{ $item->title }}
                    </div>
                    @if($item->description)
                    <div style="font-size: 13px; color: var(--gray);">{{ $item->description }}</div>
                    @endif
                    <div style="display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap;">
                        @if($item->recommended_week)
                        <span style="font-size: 11px; background: rgba(236, 72, 153, 0.1); color: var(--primary); padding: 2px 8px; border-radius: 10px;">
                            Week {{ $item->recommended_week }}
                        </span>
                        @endif
                        @if($item->is_completed)
                        <span style="font-size: 11px; color: var(--success);">
                            <i class="fas fa-check-circle"></i> Completed {{ $item->completed_at->diffForHumans() }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@else
<div class="card" style="text-align: center; padding: 60px 20px;">
    <i class="fas fa-clipboard-list" style="font-size: 64px; color: var(--primary); margin-bottom: 24px; opacity: 0.5;"></i>
    <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px;">No Checklist Items Yet</h3>
    <p style="color: var(--gray); max-width: 400px; margin: 0 auto;">
        Your personalized checklist will appear here once it's set up by your healthcare provider.
    </p>
</div>
@endif
@endsection
