<?php

namespace Tests\Unit;

use App\Models\Certificate;
use App\Models\GatewayUser;
use Carbon\Carbon;

test('expired certificate is not valid', function (){
    $certificate = Certificate::factory()->create([
        'valid_to' => Carbon::yesterday(),
    ]);
    $user = GatewayUser::factory()->create([
        'certificate_id' => $certificate->id,
    ]);

    expect($user->certificate->isValid())->toBeFalse();
});

test('not expired certificate is valid', function (){
    $certificate = Certificate::factory()->create([
        'valid_to' => Carbon::tomorrow(),
    ]);
    $user = GatewayUser::factory()->create([
        'certificate_id' => $certificate->id,
    ]);

    expect($user->certificate->isValid())->toBeTrue();
});

test('expiring certificate is expiring', function (Carbon $validTo){
    $certificate = Certificate::factory()->create([
        'valid_to' => $validTo,
    ]);
    $user = GatewayUser::factory()->create([
        'certificate_id' => $certificate->id,
    ]);

    expect($user->certificate->isExpiring())->toBeTrue();
})->with([
    'expired yesterday' => fn() => Carbon::yesterday(),
    'will expire before max number of days' => fn() => Carbon::yesterday()->addDays(config('apigw.days_before_expiration'))
]);

test('not expiring certificate return not expiring', function (){
    $certificate = Certificate::factory()->create([
        'valid_to' => Carbon::tomorrow()->addDays(config('apigw.days_before_expiration')),
    ]);
    $user = GatewayUser::factory()->create([
        'certificate_id' => $certificate->id,
    ]);

    expect($user->certificate->isExpiring())->toBeFalse();
});
