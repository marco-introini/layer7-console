<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('gateway_id')
                ->unique()
                ->after('name');
            $table->json('backends')
                ->nullable()
                ->after('url');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('gateway_id');
            $table->dropColumn('backends');
        });
    }
};
