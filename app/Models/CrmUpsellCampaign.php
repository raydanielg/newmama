<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmUpsellCampaign extends Model
{
    protected $fillable = [
        'name',
        'channel',
        'offer_text',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(CrmUpsellLead::class);
    }
}
