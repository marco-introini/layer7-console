<?php

use App\Enumerations\CertificateRequestStatus;
use App\Models\Company;
use App\Models\PublicService;
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
            $table->string('status')
                ->default(CertificateRequestStatus::REQUESTED->value);
            $table->foreignIdFor(User::class)
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(PublicService::class)
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(Company::class)
                ->constrained()
                ->nullOnDelete();
            $table->string('common_name');
            $table->dateTime('requested_at');
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');
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
