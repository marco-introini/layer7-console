<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gateway extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'gateway_id');
    }

    public function gatewayUsers(): HasMany
    {
        return $this->hasMany(GatewayUser::class, 'gateway_id');
    }

    public function ignoredUsers(): HasMany
    {
        return $this->hasMany(IgnoredUser::class, 'gateway_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'gateway_id');
    }

}
