<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ElmsTrainer extends Model
{
    protected $table = 'elms_trainers';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'specialization',
        'bio',
        'photo_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(ElmsCourse::class, 'elms_course_trainer', 'trainer_id', 'course_id');
    }
}
