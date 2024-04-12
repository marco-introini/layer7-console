<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\User;

class UserObserver
{
    public function creating(User $user): void
    {
        if (auth()->check()) {
            if (! is_null(auth()->user()->company_id)) {
                $user->setCompanyUser(Company::whereId(auth()->user()->company_id)->first());
            }
        }
    }
}

