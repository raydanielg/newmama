<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherWeightLog extends Model
{
    protected $fillable = [
        'mother_id',
        'weight_kg',
        'weeks_pregnant',
        'recorded_date',
        'notes',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'recorded_date' => 'date',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function getWeightGainFromStartAttribute(): ?float
    {
        $firstLog = $this->mother->weightLogs()->orderBy('recorded_date')->first();
        if (!$firstLog || $firstLog->id === $this->id) {
            return null;
        }
        return round($this->weight_kg - $firstLog->weight_kg, 2);
    }

    public function getBmiAttribute(): ?float
    {
        if (!$this->mother || !$this->mother->metadata) {
            return null;
        }
        $heightM = $this->mother->metadata['height_m'] ?? null;
        if (!$heightM || $heightM <= 0) {
            return null;
        }
        return round($this->weight_kg / ($heightM * $heightM), 1);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('recorded_date')->orderByDesc('id');
    }
}
