<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    protected $fillable = [
        'ref',
        'posting_date',
        'description',
        'journal_type',
        'source_type',
        'source_ref',
        'posted_by',
        'status',
        'branch',
    ];

    protected $casts = [
        'posting_date' => 'date',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }
}
