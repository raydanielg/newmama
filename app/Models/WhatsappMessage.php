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
        'to_number',
        'message',
        'wa_message_id',
        'external_id',
        'status',
        'sent_at',
        'delivered_at',
        'read_at',
        'meta',
        'metadata',
        'response_data',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'meta' => 'array',
        'metadata' => 'array',
        'response_data' => 'array',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    /**
     * Scope for pending messages
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for sent messages
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for delivered messages
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for failed messages
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if message is read
     */
    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'sent' => 'success',
            'delivered' => 'info',
            'read' => 'primary',
            'failed' => 'danger',
            'pending' => 'warning',
            default => 'secondary',
        };
    }
}
