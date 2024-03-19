<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IgnoredUser extends Model
{
    protected $guarded = [];

    /** @return BelongsTo<GatewayUser, Gateway> */
    public function gatewayUser(): BelongsTo
    {
        return $this->belongsTo(GatewayUser::class);
    }

    /** @return BelongsTo<IgnoredUser, Gateway> */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

}
