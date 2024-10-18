<?php

use App\Models\Gateway;
use App\Models\GatewayService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_services', function (Blueprint $table) {
            $table->dropColumn('gateway_service_id');
            $table->foreignIdFor(Gateway::class)
                ->after('description')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('gateway_service_name')
                ->nullable()
                ->after('gateway_id');
        });
    }

    public function down(): void
    {
        Schema::table('public_services', function (Blueprint $table) {
            $table->foreignIdFor(GatewayService::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }
};
