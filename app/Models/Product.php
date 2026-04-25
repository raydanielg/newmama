<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'category',
        'cost_price',
        'selling_price',
        'qty_on_hand',
        'image_url',
        'barcode',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'qty_on_hand' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
