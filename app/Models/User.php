<?php

namespace App\Models;

use App\Observers\UserObserver;
use Exception;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'admin' => 'boolean',
        'super_admin' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        try {
            if ($panel->getId() == 'admin') {
                return $this->super_admin;
            }
            if ($panel->getId() == 'user') {
                return ! is_null($this->company);
            }
        } catch (Exception $ignoredException) {
        }

        return false;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Filament Multi Tenancy

    /** @param  Company  $tenant */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->company->id === $tenant->id;
    }

    public function getTenants(Panel $panel): array|Collection
    {
        // for now this is a really simple case of multi tenancy with a single company associated
        return [$this->company];
    }

    public function isActive(): bool
    {
        return ! is_null($this->email_verified_at);
    }
}
