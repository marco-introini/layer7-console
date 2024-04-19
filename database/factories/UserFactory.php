<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'company_id' => null,
        ];
    }

    public function active(): UserFactory
    {
        return $this->state(function (array $attributes): array {
            return [
                'email_verified_at' => now(),
            ];
        });
    }

    public function forCompany(Company $company): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => $company->id,
        ]
        );
    }
}
