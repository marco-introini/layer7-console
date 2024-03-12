<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class GatewayUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'valid_to' => 'datetime',
        'valid_from' => 'datetime',
    ];

    /**
     * @return Attribute<bool,null>
     */
    protected function valid(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->isValid(),
        );
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
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
