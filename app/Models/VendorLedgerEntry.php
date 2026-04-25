<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorLedgerEntry extends Model
{
    protected $fillable = [
        'supplier_id',
        'posting_date',
        'document_type',
        'document_ref',
        'description',
        'amount_tzs',
        'amount',
        'remaining_amount',
        'is_open',
        'due_date',
        'journal_id',
        'import_order_ref',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'due_date' => 'date',
        'is_open' => 'boolean',
        'amount_tzs' => 'decimal:2',
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
