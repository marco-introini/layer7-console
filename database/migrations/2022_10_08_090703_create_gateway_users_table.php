<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gateway_users', function (Blueprint $table) {
            $table->id();

            $table->string('gateway_user_id')
                ->unique();
            $table->string('username')
                ->unique();
            $table->string('detail_uri')
                ->nullable();
            $table->string('common_name')
                ->nullable();
            $table->dateTime('valid_from')
                ->nullable();
            $table->dateTime('valid_to')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
