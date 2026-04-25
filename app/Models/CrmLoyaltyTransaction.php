<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmLoyaltyTransaction extends Model
{
    protected $fillable = [
        'crm_loyalty_account_id',
        'posting_date',
        'type',
        'points',
        'reference',
        'description',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'points' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(CrmLoyaltyAccount::class, 'crm_loyalty_account_id');
    }
}
