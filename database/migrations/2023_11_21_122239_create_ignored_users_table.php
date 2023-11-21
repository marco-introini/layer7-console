<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ignored_users', function (Blueprint $table) {
            $table->id();
            $table->string('userid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ignored_users');
    }
};
