<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\GatewayUser;
use App\Models\GatewayService;
use App\Models\PublicService;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        GatewayUser::factory(20)->create();
        GatewayService::factory(5)->create();
        PublicService::factory(3)->create();
        Certificate::factory(10)->create();
    }
}
