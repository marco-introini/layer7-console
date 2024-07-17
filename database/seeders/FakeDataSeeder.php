<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\GatewayUser;
use App\Models\GatewayService;
use App\Models\PublicService;
use App\Models\User;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    public function run(): void
    {
        GatewayUser::factory(20)->create();
        GatewayService::factory(5)->create();
        Company::factory(5)->create();
        User::factory(20)
            ->forCompany(Company::inRandomOrder()->first() ?? Company::factory()->create())
            ->create();
        PublicService::factory(3)->create();
        Certificate::factory(5)->create();
        Certificate::factory(2)->issued()->create();
    }
}
