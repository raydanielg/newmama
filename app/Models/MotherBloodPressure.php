<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherBloodPressure extends Model
{
    protected $fillable = [
        'mother_id',
        'systolic',
        'diastolic',
        'heart_rate',
        'recorded_at',
        'notes',
    ];

    protected $casts = [
        'systolic' => 'integer',
        'diastolic' => 'integer',
        'heart_rate' => 'integer',
        'recorded_at' => 'datetime',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function getIsNormalAttribute(): bool
    {
        // Normal BP: systolic < 140 and diastolic < 90
        return $this->systolic < 140 && $this->diastolic < 90;
    }

    public function getIsElevatedAttribute(): bool
    {
        // Elevated: systolic 120-139 or diastolic 80-89
        return ($this->systolic >= 120 && $this->systolic < 140) ||
               ($this->diastolic >= 80 && $this->diastolic < 90);
    }

    public function getIsHighAttribute(): bool
    {
        // High BP (Hypertension): systolic >= 140 or diastolic >= 90
        return $this->systolic >= 140 || $this->diastolic >= 90;
    }

    public function getIsSevereAttribute(): bool
    {
        // Severe: systolic >= 160 or diastolic >= 110
        return $this->systolic >= 160 || $this->diastolic >= 110;
    }

    public function getSeverityLevelAttribute(): string
    {
        if ($this->is_severe) return 'critical';
        if ($this->is_high) return 'high';
        if ($this->is_elevated) return 'elevated';
        return 'normal';
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        $classes = [
            'normal' => 'bg-green-100 text-green-800',
            'elevated' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
        ];
        return $classes[$this->severity_level] ?? 'bg-gray-100 text-gray-800';
    }

    public function getMapAttribute(): float
    {
        // Mean Arterial Pressure = (2*DBP + SBP) / 3
        return round((2 * $this->diastolic + $this->systolic) / 3, 1);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('recorded_at');
    }
}
