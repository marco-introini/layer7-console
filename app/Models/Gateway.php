<?php

namespace App\Models;

use App\Exceptions\GatewayConnectionException;
use App\Services\XmlService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;

class Gateway extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @throws GatewayConnectionException
     */
    public function getGatewayResponse(string $endpoint): array
    {
        $response = Http::withBasicAuth($this->admin_user, $this->admin_password)
            // need to disable server certificate verification because it's often a self-signed one
            ->withOptions([
                'verify' => false,
            ])
            ->get($this->host.$endpoint);

        if ($response->status() != 200) {
            throw new GatewayConnectionException('Connection Error: '.$response->status());
        }

        return XmlService::xml2array($response->body());
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(GatewayCertificate::class, 'gateway_id');
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
        return $this->hasMany(GatewayService::class, 'gateway_id');
    }
}
