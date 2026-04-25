<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    protected $fillable = [
        'investor_number',
        'name',
        'phone',
        'email',
        'id_number',
        'address',
        'status',
        'balance',
        'notes',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(InvestorTransaction::class);
    }
}
