<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherChecklistItem extends Model
{
    protected $fillable = [
        'mother_id',
        'category',
        'title',
        'description',
        'recommended_week',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'recommended_week' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        $labels = [
            'nutrition' => 'Nutrition & Diet',
            'exercise' => 'Exercise & Fitness',
            'tests' => 'Medical Tests',
            'preparation' => 'Preparation',
            'mental_health' => 'Mental Health',
            'education' => 'Education',
        ];
        return $labels[$this->category] ?? 'General';
    }

    public function getCategoryIconAttribute(): string
    {
        $icons = [
            'nutrition' => 'fa-utensils',
            'exercise' => 'fa-person-walking',
            'tests' => 'fa-vial',
            'preparation' => 'fa-suitcase-medical',
            'mental_health' => 'fa-brain',
            'education' => 'fa-book-open',
        ];
        return $icons[$this->category] ?? 'fa-check-circle';
    }

    public function markAsComplete(): void
    {
        if (!$this->is_completed) {
            $this->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }
    }

    public function markAsIncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeForWeek($query, int $week)
    {
        return $query->where('recommended_week', $week);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
