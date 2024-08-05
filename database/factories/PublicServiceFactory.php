<?php

namespace Database\Factories;

use App\Models\Gateway;
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
            'name' => 'SERVICE_'.fake()->unique()->randomNumber(),
            'description' => fake()->text(),
            'gateway_id' => Gateway::inRandomOrder()->first() ?? Gateway::factory()->create(),
            'gateway_service_name' => GatewayService::inRandomOrder()->first()->name ?? GatewayService::factory()->create()->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
