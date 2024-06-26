<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GatewayService extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'backends' => 'array',
    ];

    /** @return BelongsTo<GatewayService, Gateway> */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function publicService(): HasOne
    {
        return $this->hasOne(PublicService::class, 'gateway_service_id');
    }
}
