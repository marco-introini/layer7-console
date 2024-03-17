<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    /** @var string[] */
    protected $with = ['certificate'];

    protected $casts = [];

    /** @return BelongsTo<Certificate, GatewayUser> */
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
}
