<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmPreorder extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_name',
        'phone',
        'product_name',
        'qty',
        'expected_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'expected_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
