<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'backends' => 'array',
    ];

    /** @return BelongsTo<Service, Gateway> */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }
}
