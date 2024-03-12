<?php

namespace App\Models;

use App\Enumerations\CertificateType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Certificate extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => CertificateType::class,
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(GatewayUser::class,'gateway_users','certificate_id');
    }

    /**
     * @return Attribute<bool,null>
     */
    protected function expirationDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->isExpiring(),
        );
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
        if ($this->valid_to >= Carbon::today()) {
            return true;
        } else {
            return false;
        }
    }
}
