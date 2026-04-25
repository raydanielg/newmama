<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmUpsellLead extends Model
{
    protected $fillable = [
        'crm_upsell_campaign_id',
        'customer_id',
        'customer_name',
        'phone',
        'status',
        'notes',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(CrmUpsellCampaign::class, 'crm_upsell_campaign_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
