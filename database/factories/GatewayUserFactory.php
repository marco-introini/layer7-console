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
            'userid' => $this->faker->unique()->uuid(),
            'username' => $this->faker->unique()->userName(),
            'detail_uri' => $this->faker->url(),
            'common_name' => $this->faker->name(),
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
