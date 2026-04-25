<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmLoyaltyAccount extends Model
{
    protected $fillable = [
        'customer_id',
        'points_balance',
    ];

    protected $casts = [
        'points_balance' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CrmLoyaltyTransaction::class);
    }
}
