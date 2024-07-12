<?php

namespace App\Jobs;

use App\Enumerations\CertificateRequestStatus;
use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateX509Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Certificate $certificate,
        public string $commonName,
        public ?Carbon $expirationDate
    ) {}

    public function handle(): void
    {
        if ($this->certificate->status != CertificateRequestStatus::APPROVED) {
            return;
        }
        $this->certificate->generateX509Data($this->commonName, $this->expirationDate);
        $this->certificate->status = CertificateRequestStatus::ISSUED;
        $this->certificate->save();
    }
}
