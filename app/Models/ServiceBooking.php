<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBooking extends Model
{
    protected $fillable = ['service_id', 'customer_name', 'customer_phone', 'booking_date', 'status', 'notes'];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
