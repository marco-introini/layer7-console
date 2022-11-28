<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('gatewayusers', function (Blueprint $table) {
            $table->id();

            $table->string('userid');
            $table->string('username');
            $table->string('detail_uri');
            $table->string('common_name');
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
    }
};