<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollComponent extends Model
{
    protected $fillable = [
        'name',
        'type',
        'calculation_type',
        'amount',
        'rate',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate' => 'decimal:4',
        'is_active' => 'boolean',
    ];
}
