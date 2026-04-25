<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequestItem extends Model
{
    protected $fillable = [
        'product_request_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function productRequest()
    {
        return $this->belongsTo(ProductRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
