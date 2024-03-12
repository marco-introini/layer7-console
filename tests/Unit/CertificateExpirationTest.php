<?php

namespace Tests\Unit;

use App\Models\Certificate;
use Carbon\Carbon;

test('expired certificate is not valid', function (Carbon $when) {
    $certificate = Certificate::factory()->create([
        'valid_to' => $when,
    ]);

    expect($certificate->isValid())->toBeFalse();
})->with([
    'yesterday' => Carbon::yesterday(),
    '1 hour ago' => Carbon::now()->subHour(),
    '1 minute ago' => Carbon::now()->subMinute(),
]);

test('not expired certificate is valid', function () {
    $certificate = Certificate::factory()->create([
        'valid_to' => Carbon::tomorrow(),
    ]);

    expect($certificate->isValid())->toBeTrue();
});

test('expiring certificate is expiring', function (Carbon $validTo) {
    $certificate = Certificate::factory()->create([
        'valid_to' => $validTo,
    ]);

    expect($certificate->isExpiring())->toBeTrue();
})->with([
    'expired yesterday' => fn () => Carbon::yesterday(),
    'will expire before max number of days' => fn () => Carbon::yesterday()->addDays(config('apigw.days_before_expiration')),
]);

test('not expiring certificate return not expiring', function () {
    $certificate = Certificate::factory()->create([
        'valid_to' => Carbon::tomorrow()->addDays(config('apigw.days_before_expiration')),
    ]);
    expect($certificate->isExpiring())->toBeFalse();
});
