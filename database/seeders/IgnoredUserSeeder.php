<?php

namespace Database\Seeders;

use App\Models\IgnoredUser;
use Illuminate\Database\Seeder;

class IgnoredUserSeeder extends Seeder
{
    public function run(): void
    {
        IgnoredUser::create([
            'gateway_id' => "00000000000000000000000000000003",
        ]);

        IgnoredUser::create([
            'gateway_id' => "da2b9b245c56435a4ae977ac0cc3c47a",
        ]);
    }
}
