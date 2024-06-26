<?php

use App\Models\Company;
use App\Models\GatewayService;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->foreignIdFor(User::class)
                ->constrained()
                ->onDelete('null');
            $table->foreignIdFor(GatewayService::class)
                ->constrained()
                ->onDelete('null');
            $table->foreignIdFor(Company::class)
                ->constrained()
                ->onDelete('null');
            $table->string('common_name');
            $table->dateTime('requested_at');
            $table->text('private_key');
            $table->text('public_cert');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
