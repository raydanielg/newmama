<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherDailyLog extends Model
{
    protected $fillable = [
        'mother_id',
        'log_date',
        'mood',
        'symptoms',
        'notes',
        'sleep_hours',
        'water_intake_glasses',
    ];

    protected $casts = [
        'log_date' => 'date',
        'symptoms' => 'array',
        'sleep_hours' => 'decimal:1',
        'water_intake_glasses' => 'integer',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function getMoodLabelAttribute(): string
    {
        $labels = [
            'great' => 'Great!',
            'good' => 'Good',
            'okay' => 'Okay',
            'tired' => 'Tired',
            'sad' => 'Sad',
            'anxious' => 'Anxious',
        ];
        return $labels[$this->mood] ?? 'Not recorded';
    }

    public function getMoodIconAttribute(): string
    {
        $icons = [
            'great' => 'fa-face-laugh-beam',
            'good' => 'fa-face-smile',
            'okay' => 'fa-face-meh',
            'tired' => 'fa-face-tired',
            'sad' => 'fa-face-frown',
            'anxious' => 'fa-face-grimace',
        ];
        return $icons[$this->mood] ?? 'fa-circle-question';
    }

    public function getMoodColorAttribute(): string
    {
        $colors = [
            'great' => '#22c55e',
            'good' => '#3b82f6',
            'okay' => '#f59e0b',
            'tired' => '#6b7280',
            'sad' => '#6366f1',
            'anxious' => '#f43f5e',
        ];
        return $colors[$this->mood] ?? '#9ca3af';
    }

    public function getCommonSymptomsList(): array
    {
        return [
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
    }

    public function getSymptomsLabelsAttribute(): array
    {
        $list = $this->getCommonSymptomsList();
        $labels = [];
        foreach ($this->symptoms ?? [] as $symptom) {
            $labels[] = $list[$symptom] ?? ucwords(str_replace('_', ' ', $symptom));
        }
        return $labels;
    }

    public function scopeToday($query)
    {
        return $query->where('log_date', today());
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('log_date');
    }
}
