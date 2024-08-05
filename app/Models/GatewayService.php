<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GatewayService extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'backends' => 'array',
    ];

    /** @return BelongsTo<Gateway, GatewayService> */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function publicServices(): HasMany
    {
        return $this->hasMany(PublicService::class, 'gateway_service_name', 'name');
    }
}
