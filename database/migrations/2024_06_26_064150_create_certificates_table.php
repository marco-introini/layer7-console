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
                ->restrictOnDelete();
            $table->foreignIdFor(PublicService::class)
                ->constrained()
                ->restrictOnDelete();
            $table->foreignIdFor(Company::class)
                ->constrained()
                ->restrictOnDelete();
            $table->dateTime('requested_at')
                ->default(now());
            $table->string('common_name')
                ->nullable();
            $table->dateTime('valid_from')
                ->nullable();
            $table->dateTime('valid_to')
                ->nullable();
            $table->text('private_key')
                ->nullable();
            $table->text('public_cert')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
