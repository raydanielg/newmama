<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'payment_method',
        'payment_reference',
        'total_amount',
        'status',
        'expires_at',
        'payment_link_token',
    ];

    public function items()
    {
        return $this->hasMany(ProductRequestItem::class);
    }
}
