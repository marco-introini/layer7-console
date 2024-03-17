<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\GatewayUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class GatewayUserFactory extends Factory
{
    protected $model = GatewayUser::class;

    public function definition(): array
    {
        return [
            'gateway_id' => fake()->unique()->uuid(),
            'username' => fake()->unique()->userName(),
            'detail_uri' => fake()->url(),
            'certificate_id' => Certificate::factory()->create()->id,
        ];
    }
}
