<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['certificate'];

    protected $casts = [];

    /** @return BelongsTo<GatewayCertificate, GatewayUser> */
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(GatewayCertificate::class, 'gateway_certificate_id');
    }

    /** @return BelongsTo<Gateway, GatewayUser> */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }

    public function isExpiring(): bool
    {
        if (is_null($this->certificate) || $this->certificate == '') {
            return false;
        }

        return $this->certificate->isExpiring();
    }
}
