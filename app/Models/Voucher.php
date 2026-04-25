<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'ref',
        'type',
        'posting_date',
        'due_date',
        'description',
        'subtotal',
        'vat_amount',
        'total_amount',
        'status',
        'branch',
        'supplier_id',
        'customer_id',
        'payment_terms',
        'journal_id',
        'payment_method',
        'notes',
        'posted_by',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(VoucherLine::class);
    }
}
