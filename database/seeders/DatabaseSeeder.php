<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
           'name' => 'admin',
           'email' => 'layer7@admin.com',
           'password' => Hash::make('layer7'),
        ]);
    }
}
