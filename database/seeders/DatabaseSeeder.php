<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()
            ->superAdmin()
            ->create([
                'name' => 'superadmin',
                'email' => 'layer7@admin.com',
                'password' => Hash::make('layer7'),
            ]);

        User::factory()
            ->admin()
            ->create([
                'name' => 'companyadmin',
                'email' => 'user@mycompany.com',
                'password' => Hash::make('layer7'),
                'company_id' => Company::inRandomOrder()->first() ?? Company::factory()->create(),
            ]);

    }
}
