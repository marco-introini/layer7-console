<?php

namespace App\Models;

use App\Enumerations\CertificateRequestStatus;
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
            CertificateRequestStatus::APPROVED, CertificateRequestStatus::ISSUED => 'success',
            CertificateRequestStatus::REQUESTED => 'warning',
            CertificateRequestStatus::REJECTED => 'danger',
            default => 'gray',
        };
    }
}
