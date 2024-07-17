<?php

namespace Database\Factories;

use App\Enumerations\CertificateRequestStatus;
use App\Models\Certificate;
use App\Models\PublicService;
use App\Models\User;
use App\Services\X509Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        $user = User::query()
            ->where('admin', false)
            ->where('super_admin', false)
            ->inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'status' => CertificateRequestStatus::REQUESTED,
            'public_service_id' => PublicService::inRandomOrder()->first()->id ?? PublicService::factory()->create()->id,
            'company_id' => $user->company_id,
            'requested_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function issued(): CertificateFactory
    {
        $commonName = 'COMMON_NAME_'.fake()->randomNumber();
        $x509 = X509Service::generateCertificate($commonName, null);

        return $this->state(fn (array $attributes) => [
            'status' => CertificateRequestStatus::ISSUED,
            'common_name' => $commonName,
            'private_key' => $x509->privateKey,
            'public_cert' => $x509->certificate->pemCertificate,
            'valid_from' => $x509->certificate->validFrom,
            'valid_to' => $x509->certificate->validTo,
        ]);
    }
}
