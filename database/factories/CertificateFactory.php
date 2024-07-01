<?php

namespace Database\Factories;

use App\Enumerations\CertificateRequestStatus;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\PublicService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition(): array
    {
        $user = User::query()
            ->where('admin',false)
            ->where('super_admin', false)
            ->inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'status' => fake()->randomElement(CertificateRequestStatus::cases()),
            'public_service_id' => PublicService::inRandomOrder()->first()->id ?? PublicService::factory()->create()->id,
            'company_id' => $user->company_id,
            'common_name' => 'COMMON_NAME_'.fake()->randomNumber(),
            'requested_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
