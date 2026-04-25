<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PosBundle extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'pos_bundle_items', 'pos_bundle_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
