<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElmsCourse extends Model
{
    protected $table = 'elms_courses';

    protected $fillable = [
        'code',
        'title',
        'description',
        'category',
        'level',
        'duration_hours',
        'base_price',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'duration_hours' => 'integer',
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function fees(): HasMany
    {
        return $this->hasMany(ElmsCourseFee::class, 'course_id');
    }

    public function activeFees(): HasMany
    {
        return $this->fees()->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function trainers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ElmsTrainer::class, 'elms_course_trainer', 'course_id', 'trainer_id');
    }
}
