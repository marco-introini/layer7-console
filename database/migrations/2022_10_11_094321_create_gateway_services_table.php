<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('gateway_services', function (Blueprint $table) {
            $table->id();

            $table->text('name');
            $table->text('url');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateway_services');
    }
};
