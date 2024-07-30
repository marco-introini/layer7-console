<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    /** @return HasMany<User> */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id');
    }

    /** @return HasMany<Certificate> */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'company_id');
    }
}
