<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmReferral extends Model
{
    protected $fillable = [
        'referrer_customer_id',
        'referrer_name',
        'referee_name',
        'referee_phone',
        'reward_amount',
        'status',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
    ];

    public function referrerCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referrer_customer_id');
    }
}
