<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_number',
        'customer_type',
        'name',
        'company',
        'contact_person',
        'segment',
        'whatsapp',
        'email',
        'phone',
        'address',
        'credit_limit',
        'credit_period',
        'payment_terms',
        'balance',
        'crown_points',
        'is_active',
        'last_purchase_date',
        'last_purchase_amount',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
        'balance' => 'decimal:2',
        'last_purchase_amount' => 'decimal:2',
        'last_purchase_date' => 'date',
    ];

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(CustomerLedgerEntry::class);
    }
}
