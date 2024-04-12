<?php

namespace Tests\Observers;

use App\Enumerations\UserRoleEnum;
use App\Models\Company;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    createRoles();
});


it('create a company user by a Company Admin: company and role', function () {
    $company = Company::factory()->create();
    $myUser = User::factory()->active()->create();
    $myUser->setCompanyAdmin($company);
    $myUser->save();

    actingAs($myUser);
    $user = User::factory()->active()->create();
    $user->refresh();

    expect($user->company)->not->toBeNull()
        ->and($company->id)->toBe($user->company->id)
        ->and($user->hasRole(UserRoleEnum::COMPANY_USER))->toBeTrue();
});

it('create a NON company user by default', function () {
    $user = User::factory()->active()->create();

    expect($user->company)->toBeNull()
        ->and($user->hasRole(UserRoleEnum::COMPANY_USER))->toBeFalse();
});
