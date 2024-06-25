<?php

namespace Database\Seeders;

use App\Models\GatewayUser;
use App\Models\GatewayService;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        GatewayUser::factory(20)->create();
        GatewayService::factory(5)->create();
    }
}
