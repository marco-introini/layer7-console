<?php

namespace App\Models;

use App\Enumerations\UserRoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
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
        if ($panel->getId() == 'admin') {
            return $this->hasRole(UserRoleEnum::ADMIN);
        }
        if ($panel->getId() == 'user') {
            return $this->hasRole(UserRoleEnum::SOLO_USER) || $this->hasRole(UserRoleEnum::COMPANY_USER);
        }

        return false;
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            // here we must decide which role the created user has
            // for now the default is SOLO
            if (! $user->hasAnyRole()) {
                $user->assignRole(UserRoleEnum::SOLO_USER);
            }
        });
    }
}
