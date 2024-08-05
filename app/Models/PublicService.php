<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function gatewayService(): BelongsTo
    {
        return $this->belongsTo(GatewayService::class,'gateway_service_name','name');
    }

}
