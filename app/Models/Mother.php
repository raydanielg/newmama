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
        'metadata'
    ];

    protected $casts = [
        'edd_date' => 'date',
        'metadata' => 'array',
        'baby_age' => 'integer',
        'is_approved' => 'boolean',
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
}
