<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PosOrderItem extends Model
{
    protected $fillable = [
        'pos_order_id',
        'sellable_type',
        'sellable_id',
        'name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function sellable(): MorphTo
    {
        return $this->morphTo();
    }
}
