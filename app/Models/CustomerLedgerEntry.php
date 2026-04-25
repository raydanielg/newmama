<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLedgerEntry extends Model
{
    protected $fillable = [
        'customer_id',
        'posting_date',
        'document_type',
        'document_ref',
        'description',
        'amount',
        'remaining_amount',
        'is_open',
        'due_date',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'due_date' => 'date',
        'is_open' => 'boolean',
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
