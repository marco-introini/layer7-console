<?php

namespace Database\Seeders;

use App\Models\GatewayUser;
use App\Models\Service;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        GatewayUser::factory(20)->create();
        Service::factory(5)->create();
    }
}
