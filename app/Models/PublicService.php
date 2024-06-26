<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function gatewayService(): BelongsTo
    {
        return $this->belongsTo(GatewayService::class);
    }

}
