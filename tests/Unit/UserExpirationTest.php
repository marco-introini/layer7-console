<?php

namespace Tests\Unit;

use App\Models\GatewayUser;
use Carbon\Carbon;

test('expired certificate is not valid', function (){
    $user = GatewayUser::factory()->create([
        'valid_to' => Carbon::yesterday(),
    ]);

    expect($user->isValid())->toBeFalse();
});

test('not expired certificate is valid', function (){
    $user = GatewayUser::factory()->create([
        'valid_to' => Carbon::tomorrow(),
    ]);

    expect($user->isValid())->toBeTrue();
});

test('expiring certificate is expiring', function (Carbon $validTo){
    $user = GatewayUser::factory()->create([
        'valid_to' => $validTo,
    ]);

    expect($user->isExpiring())->toBeTrue();
})->with([
    'expired yesterday' => fn() => Carbon::yesterday(),
    'will expire before max number of days' => fn() => Carbon::yesterday()->addDays(config('apigw.days_before_expiration'))
]);

test('not expiring certificate return not expiring', function (){
    $user = GatewayUser::factory()->create([
        'valid_to' => Carbon::tomorrow()->addDays(config('apigw.days_before_expiration')),
    ]);

    expect($user->isExpiring())->toBeFalse();
});
