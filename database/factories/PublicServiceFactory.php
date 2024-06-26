<?php

namespace Database\Factories;

use App\Models\GatewayService;
use App\Models\PublicService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PublicServiceFactory extends Factory
{
    protected $model = PublicService::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'gateway_service_id' => GatewayService::inRandomOrder()->first() ?? GatewayService::factory()->create(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
