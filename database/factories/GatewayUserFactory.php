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
            'userid' => $this->faker->word(),
            'username' => $this->faker->userName(),
            'detail_uri' => $this->faker->word(),
            'common_name' => $this->faker->name(),
            'valid_from' => Carbon::now(),
            'valid_to' => Carbon::now(),
            'ignored' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
