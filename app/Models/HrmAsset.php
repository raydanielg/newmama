<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HrmAsset extends Model
{
    protected $table = 'hrm_assets';

    protected $fillable = [
        'asset_tag',
        'name',
        'category',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'condition',
        'status',
        'assigned_employee_id',
        'assigned_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'assigned_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }
}
