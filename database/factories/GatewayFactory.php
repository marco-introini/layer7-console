<?php

namespace Database\Factories;

use App\Models\Gateway;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GatewayFactory extends Factory
{
    protected $model = Gateway::class;

    public function definition(): array
    {
        return [
            'host' => $this->faker->word(),
            'identity_provider' => $this->faker->word(),
            'admin_user' => $this->faker->word(),
            'admin_password' => bcrypt($this->faker->password()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
