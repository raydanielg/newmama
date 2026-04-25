<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportOrder extends Model
{
    protected $fillable = [
        'ref',
        'posting_date',
        'supplier_id',
        'supplier_name',
        'source_file_name',
        'total_cost',
        'total_lines',
        'status',
        'import_type',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'posting_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(ImportOrderLine::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
