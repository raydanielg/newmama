<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'code',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'payment_terms',
        'balance_tzs',
        'balance_usd',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance_tzs' => 'decimal:2',
        'balance_usd' => 'decimal:2',
    ];

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(VendorLedgerEntry::class);
    }
}
