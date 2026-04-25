<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherHealthAlert extends Model
{
    protected $fillable = [
        'mother_id',
        'alert_type',
        'severity',
        'message',
        'recommendation',
        'is_read',
        'read_at',
        'is_resolved',
        'resolved_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_resolved' => 'boolean',
        'read_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function getAlertTypeLabelAttribute(): string
    {
        $labels = [
            'preeclampsia' => 'Pre-eclampsia Warning',
            'anemia' => 'Anemia Alert',
            'gestational_diabetes' => 'Gestational Diabetes',
            'high_bp' => 'High Blood Pressure',
            'low_kick_count' => 'Low Baby Movement',
            'missed_appointment' => 'Missed Appointment',
            'overdue_checkup' => 'Overdue Checkup',
            'weight_gain' => 'Abnormal Weight Gain',
            'infection' => 'Possible Infection',
            'other' => 'Other Health Concern',
        ];
        return $labels[$this->alert_type] ?? 'Health Alert';
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        $classes = [
            'low' => 'bg-blue-100 text-blue-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
        ];
        return $classes[$this->severity] ?? 'bg-gray-100 text-gray-800';
    }

    public function getIconAttribute(): string
    {
        $icons = [
            'preeclampsia' => 'fa-triangle-exclamation',
            'anemia' => 'fa-droplet',
            'gestational_diabetes' => 'fa-cube',
            'high_bp' => 'fa-heart-pulse',
            'low_kick_count' => 'fa-baby',
            'missed_appointment' => 'fa-calendar-xmark',
            'overdue_checkup' => 'fa-clock',
            'weight_gain' => 'fa-weight-scale',
            'infection' => 'fa-virus',
            'other' => 'fa-circle-exclamation',
        ];
        return $icons[$this->alert_type] ?? 'fa-exclamation-circle';
    }

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsResolved(): void
    {
        if (!$this->is_resolved) {
            $this->update([
                'is_resolved' => true,
                'resolved_at' => now(),
            ]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }
}
