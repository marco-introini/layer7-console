<?php

namespace Database\Factories;

use App\Models\GatewayUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GatewayUserFactory extends Factory
{
    protected $model = GatewayUser::class;

    public function definition(): array
    {
        return [
            'userid' => fake()->unique()->uuid(),
            'username' => fake()->unique()->userName(),
            'detail_uri' => fake()->url(),
            'common_name' => fake()->name(),
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
