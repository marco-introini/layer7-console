<?php

namespace Database\Factories;

use App\Models\Gateway;
use App\Models\GatewayService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ServiceFactory extends Factory
{
    protected $model = GatewayService::class;

    public function definition(): array
    {
        return [
            'gateway_id' => Gateway::inRandomOrder()->first()->id ?? Gateway::factory()->create()->id,
            'name' => fake()->unique()->name(),
            'gateway_service_id' => fake()->unique()->uuid(),
            'url' => fake()->url(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
