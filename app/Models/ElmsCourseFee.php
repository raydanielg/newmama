<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElmsCourseFee extends Model
{
    protected $table = 'elms_course_fees';

    protected $fillable = [
        'course_id',
        'name',
        'amount',
        'currency',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(ElmsCourse::class, 'course_id');
    }
}
