<?php

namespace Database\Seeders;

use App\Models\Gateway;
use App\Models\IgnoredUser;
use Illuminate\Database\Seeder;

class RealDataSeeder extends Seeder
{
    /*
     * This seeder is used to qui
     */
    public function run(): void
    {

        $defaultGateway = Gateway::create([
            'name' => 'Default Gateway',
            'host' => env('APIGW_HOST'),
            'identity_provider' => env('APIGW_IDENTITY_PROVIDER'),
            'admin_user' => env('APIGW_USER'),
            'admin_password' => env('APIGW_PASSWORD'),
        ]);

        IgnoredUser::create([
            'gateway_id' => $defaultGateway->id,
            'gateway_user_id' => '00000000000000000000000000000003',
        ]);

        IgnoredUser::create([
            'gateway_id' => $defaultGateway->id,
            'gateway_user_id' => 'da2b9b245c56435a4ae977ac0cc3c47a',
        ]);
    }
}
