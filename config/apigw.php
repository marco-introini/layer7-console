<?php

return [
    'hostname' => env('APIGW_HOST'),
    'user' => env('APIGW_USER'),
    'password' => env('APIGW_PASSWORD'),
    'identityProvider' => env('APIGW_IDENTITY_PROVIDER', '0000000000000000fffffffffffffffe'),
    'days_before_expiration' => env('DAYS_ALERT_CERT'),
];
