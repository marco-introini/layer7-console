<?php

namespace App\Models;

use App\Enumerations\CertificateRequestStatus;
use App\Services\X509Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'requested_at' => 'datetime',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'status' => CertificateRequestStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function publicService(): BelongsTo
    {
        return $this->belongsTo(PublicService::class);
    }

    public function getFilamentColor(): string
    {
        return match ($this->status) {
            CertificateRequestStatus::ISSUED => 'success',
            CertificateRequestStatus::REQUESTED => 'warning',
            CertificateRequestStatus::REJECTED => 'danger',
            default => 'gray',
        };
    }

    public function isApprovable(): bool
    {
        if ($this->status === CertificateRequestStatus::REQUESTED) {
            return true;
        }

        return false;
    }

    public function canBeRegenerated(): bool
    {
        if ($this->status === CertificateRequestStatus::ISSUED) {
            return true;
        }

        return false;
    }

    public function generateX509Data(string $commonName, ?Carbon $expirationDate = null): void
    {
        $x509Data = X509Service::generateCertificate($commonName, $expirationDate);

        $this->private_key = $x509Data->privateKey;
        $this->public_cert = $x509Data->certificate->pemCertificate;
        $this->common_name = $x509Data->certificate->commonName;
        $this->valid_from = $x509Data->certificate->validFrom;
        $this->valid_to = $x509Data->certificate->validTo;
    }
}
