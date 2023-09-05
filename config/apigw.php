<?php

return [
    'hostname' => env("APIGW_HOST"),
    'user' => env("APIGW_USER"),
    'password' => env("APIGW_PASSWORD"),
    'days_before_expiration' => env('DAYS_ALERT_CERT'),
];
