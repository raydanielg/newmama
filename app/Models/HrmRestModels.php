<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HrmPerformanceReview extends Model
{
    protected $fillable = ['employee_id', 'review_date', 'reviewer_name', 'rating', 'comments'];

    protected $casts = ['review_date' => 'date'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

class HrmRecruitmentJob extends Model
{
    protected $fillable = ['title', 'department', 'description', 'status'];
}

class HrmEvent extends Model
{
    protected $fillable = ['title', 'event_date', 'location', 'description'];

    protected $casts = ['event_date' => 'date'];
}
