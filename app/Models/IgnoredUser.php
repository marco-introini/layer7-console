<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IgnoredUser extends Model
{
    protected $guarded = [];

    /** @return BelongsTo<GatewayUser, IgnoredUser> */
    public function gatewayUser(): BelongsTo
    {
        return $this->belongsTo(GatewayUser::class);
    }

    /** @return BelongsTo<Gateway, IgnoredUser> */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }
}
