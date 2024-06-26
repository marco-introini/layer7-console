<?php

namespace Database\Seeders;

use App\Enumerations\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => UserRoleEnum::ADMIN]);
        Role::create(['name' => UserRoleEnum::COMPANY_ADMIN]);
        Role::create(['name' => UserRoleEnum::COMPANY_USER]);

        User::create([
            'name' => 'admin',
            'email' => 'layer7@admin.com',
            'password' => Hash::make('layer7'),
        ])->assignRole(UserRoleEnum::ADMIN);

        User::create([
            'name' => 'companyuser',
            'email' => 'user@mycompany.com',
            'password' => Hash::make('layer7'),
        ])->assignRole(UserRoleEnum::COMPANY_ADMIN);

    }
}
