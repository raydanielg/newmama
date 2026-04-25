<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherKickCount extends Model
{
    protected $fillable = [
        'mother_id',
        'kick_count',
        'duration_minutes',
        'started_at',
        'ended_at',
        'recorded_date',
        'notes',
    ];

    protected $casts = [
        'kick_count' => 'integer',
        'duration_minutes' => 'integer',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'recorded_date' => 'date',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function getIsNormalAttribute(): bool
    {
        // Normal kick count is at least 10 kicks in 2 hours (120 minutes)
        // If duration is less, we extrapolate
        if ($this->duration_minutes <= 0) {
            return $this->kick_count >= 10;
        }
        $extrapolatedKicks = ($this->kick_count / $this->duration_minutes) * 120;
        return $extrapolatedKicks >= 10;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_normal 
            ? 'bg-green-100 text-green-800' 
            : 'bg-yellow-100 text-yellow-800';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_normal ? 'Normal' : 'Low Activity - Monitor';
    }

    public function getKicksPerHourAttribute(): float
    {
        if (!$this->duration_minutes || $this->duration_minutes <= 0) {
            return 0;
        }
        return round(($this->kick_count / $this->duration_minutes) * 60, 1);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('recorded_date')->orderByDesc('id');
    }
}
