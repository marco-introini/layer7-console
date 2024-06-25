<?php

use App\Models\GatewayCertificate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // divided in 3 calls for sql-lite limitations
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->dropColumn('common_name');
        });
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->dropColumn('valid_from');
        });
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->dropColumn('valid_to');
        });
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->foreignIdFor(GatewayCertificate::class)
                ->nullable()
                ->after('detail_uri');
        });
    }

    public function down(): void
    {
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->string('common_name')
                ->nullable();
            $table->dateTime('valid_from')
                ->nullable();
            $table->dateTime('valid_to')
                ->nullable();
        });
    }
};
