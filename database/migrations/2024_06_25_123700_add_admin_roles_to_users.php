<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('super_admin')
                ->after('name')
                ->default(false);
            $table->boolean('admin')
                ->after('super_admin')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('super_admin');
            $table->dropColumn('admin');
        });
    }
};
