<?php

use App\Models\Gateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->foreignIdFor(Gateway::class)->after('id');
        });
        Schema::table('ignored_users', function (Blueprint $table) {
            $table->foreignIdFor(Gateway::class)->after('id');
        });
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignIdFor(Gateway::class)->after('id');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->foreignIdFor(Gateway::class)->after('id');
            $table->unique(['gateway_service_id', 'gateway_id']);
        });
    }

    public function down(): void
    {
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->dropColumn('gateway_id');
        });
        Schema::table('ignored_users', function (Blueprint $table) {
            $table->dropColumn('gateway_id');
        });
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('gateway_id');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('gateway_id');
        });
    }
};
