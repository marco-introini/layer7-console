<?php

namespace App\Models;

use App\Enumerations\CertificateRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
