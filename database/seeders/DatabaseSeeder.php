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
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('layer7'),
            ]);

        $company = Company::inRandomOrder()->first() ?? Company::factory()->create();

        User::factory()
            ->admin()
            ->create([
                'name' => 'companyadmin',
                'email' => 'admin@mycompany.com',
                'password' => Hash::make('layer7'),
                'company_id' => $company,
            ]);

        User::factory()
            ->create([
                'name' => 'companyuser',
                'email' => 'user@mycompany.com',
                'password' => Hash::make('layer7'),
                'company_id' => $company,
            ]);

    }
}
