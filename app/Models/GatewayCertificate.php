<?php

namespace App\Models;

use App\Enumerations\CertificateType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class GatewayCertificate extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'type' => CertificateType::class,
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];


    /** @return BelongsToMany<GatewayUser> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(GatewayUser::class, 'gateway_users', 'gateway_certificate_id');
    }

    public function isExpiring(): bool
    {
        if ($this->valid_to >= Carbon::today()->addDays(config('apigw.days_before_expiration'))) {
            return false;
        } else {
            return true;
        }
    }

    public function isValid(): bool
    {
        if ($this->valid_to >= Carbon::now()) {
            return true;
        } else {
            return false;
        }
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }
}
