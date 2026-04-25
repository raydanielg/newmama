<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElmsLevel extends Model
{
    protected $table = 'elms_levels';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
