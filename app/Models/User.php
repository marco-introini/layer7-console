<?php

namespace App\Models;

use App\Enumerations\UserRoleEnum;
use App\Observers\UserObserver;
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
use Exception;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasTenants
{
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        try {
            if ($panel->getId() == 'admin') {
                return $this->hasRole(UserRoleEnum::ADMIN);
            }
            if ($panel->getId() == 'user') {
                return $this->hasRole(UserRoleEnum::COMPANY_USER) || $this->hasRole(UserRoleEnum::COMPANY_ADMIN);
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
    /** @param Company $tenant */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->company->id === $tenant->id;
    }

    public function getTenants(Panel $panel): array|Collection
    {
        // for now this is a really simple case of multi tenancy with a single company associated
        return [$this->company];
    }

    public function setCompanyUser(Company $company): void
    {
        $this->company_id = $company->id;
        $this->assignRole(UserRoleEnum::COMPANY_USER);
    }

    public function setCompanyAdmin(Company $company): void
    {
        $this->company_id = $company->id;
        $this->assignRole(UserRoleEnum::COMPANY_USER);
    }

    public function isActive(): bool
    {
        return ! is_null($this->email_verified_at);
    }
}
