<?php

use App\Models\Certificate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gateway_users', function (Blueprint $table) {
            $table->dropColumn('common_name');
            $table->dropColumn('valid_from');
            $table->dropColumn('valid_to');
            $table->foreignIdFor(Certificate::class)
                ->nullable()
                ->after('detail_url');
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
