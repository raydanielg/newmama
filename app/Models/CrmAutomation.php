<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmAutomation extends Model
{
    protected $fillable = [
        'name',
        'trigger_type',
        'action_type',
        'config',
        'is_active',
        'last_run_at',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];
}
