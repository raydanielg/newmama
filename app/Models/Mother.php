<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mother extends Model
{
    public const STATUSES = [
        'pregnant' => 'Pregnant',
        'new_parent' => 'New Parent',
        'trying' => 'Trying to Conceive',
    ];

    public const STATUS_COLORS = [
        'pregnant' => 'pink',
        'new_parent' => 'blue',
        'trying' => 'purple',
    ];

    protected $fillable = [
        'mk_number',
        'full_name',
        'whatsapp_number',
        'country_id',
        'region_id',
        'district_id',
        'status',
        'is_approved',
        'approved_at',
        'edd_date',
        'baby_age',
        'trying_duration',
        'current_step',
        'is_onboarded',
        'metadata'
    ];

    protected $casts = [
        'edd_date' => 'date',
        'metadata' => 'array',
        'baby_age' => 'integer',
        'is_approved' => 'boolean',
        'is_onboarded' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function getWeeksPregnantAttribute()
    {
        if ($this->status !== 'pregnant' || !$this->edd_date) {
            return null;
        }

        // Pregnancy is ~280 days
        $totalPregnancyDays = 280;
        $daysToEdd = now()->diffInDays($this->edd_date, false);
        
        $daysPregnant = $totalPregnancyDays - $daysToEdd;
        $weeks = floor($daysPregnant / 7);

        return max(0, min(42, (int)$weeks));
    }

    public function getTrimesterAttribute()
    {
        $weeks = $this->weeks_pregnant;
        if ($weeks === null) return null;

        if ($weeks <= 12) return 1;
        if ($weeks <= 26) return 2;
        return 3;
    }

    public function getCurrentStepLabelAttribute()
    {
        // Example progression mapping
        $steps = [
            '1' => 'Registration',
            '2' => 'Initial Checkup',
            '3' => 'First Trimester Guide',
            '4' => 'Second Trimester Guide',
            '5' => 'Third Trimester Guide',
            '6' => 'Delivery Prep',
            '7' => 'New Parent Onboarding',
        ];

        return $steps[$this->current_step] ?? 'Unknown';
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    protected static function booted()
    {
        static::creating(function ($mother) {
            if (empty($mother->mk_number)) {
                $latest = static::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $mother->mk_number = 'MK-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function whatsappMessages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(MotherAppointment::class);
    }

    public function weightLogs(): HasMany
    {
        return $this->hasMany(MotherWeightLog::class);
    }

    public function kickCounts(): HasMany
    {
        return $this->hasMany(MotherKickCount::class);
    }

    public function bloodPressures(): HasMany
    {
        return $this->hasMany(MotherBloodPressure::class);
    }

    public function healthAlerts(): HasMany
    {
        return $this->hasMany(MotherHealthAlert::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(MotherChecklistItem::class);
    }

    public function dailyLogs(): HasMany
    {
        return $this->hasMany(MotherDailyLog::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function getLatestWeightAttribute(): ?MotherWeightLog
    {
        return $this->weightLogs()->recent()->first();
    }

    public function getLatestBloodPressureAttribute(): ?MotherBloodPressure
    {
        return $this->bloodPressures()->recent()->first();
    }

    public function getUpcomingAppointmentsAttribute()
    {
        return $this->appointments()->upcoming()->get();
    }

    public function getPendingChecklistItemsAttribute()
    {
        return $this->checklistItems()->pending()->get();
    }

    public function getUnreadAlertsCountAttribute(): int
    {
        return $this->healthAlerts()->unread()->count();
    }

    public function getCriticalAlertsAttribute()
    {
        return $this->healthAlerts()->unresolved()->critical()->get();
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $colors = [
            'pregnant' => 'bg-pink-100 text-pink-800',
            'new_parent' => 'bg-blue-100 text-blue-800',
            'trying' => 'bg-purple-100 text-purple-800',
        ];
        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }
}
