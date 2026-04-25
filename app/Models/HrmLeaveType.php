<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HrmLeaveType extends Model
{
    protected $table = 'hrm_leave_types';

    protected $fillable = [
        'name',
        'code',
        'default_days',
        'requires_approval',
        'is_active',
    ];

    protected $casts = [
        'default_days' => 'integer',
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function requests(): HasMany
    {
        return $this->hasMany(HrmLeaveRequest::class, 'leave_type_id');
    }
}
