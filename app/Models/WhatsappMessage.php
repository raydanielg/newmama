<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappMessage extends Model
{
    protected $fillable = [
        'mother_id',
        'direction',
        'type',
        'body',
        'wa_message_id',
        'sent_at',
        'meta',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'meta' => 'array',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }
}
