<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherAppointment extends Model
{
    protected $fillable = [
        'mother_id',
        'title',
        'description',
        'appointment_date',
        'clinic_name',
        'doctor_name',
        'type',
        'status',
        'notes',
        'outcome',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'checkup' => 'Clinic Checkup',
            'ultrasound' => 'Ultrasound Scan',
            'lab_test' => 'Laboratory Test',
            'vaccination' => 'Vaccination',
            'other' => 'Other',
        ];
        return $labels[$this->type] ?? 'Unknown';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $classes = [
            'scheduled' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'missed' => 'bg-red-100 text-red-800',
        ];
        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now())
                     ->where('status', 'scheduled')
                     ->orderBy('appointment_date');
    }

    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', now())
                     ->orderByDesc('appointment_date');
    }
}
