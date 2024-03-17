<?php

use App\Console\Commands\GetCertificateNotificationCommand;
use App\Console\Commands\GetGatewayUsersInfoCommand;
use App\Console\Commands\GetPrivateKeysCommand;
use App\Console\Commands\GetServicesInfoCommand;
use App\Console\Commands\GetTrustedCertsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(GetServicesInfoCommand::class)
    ->weeklyOn(1, '7:30');
Schedule::command(GetGatewayUsersInfoCommand::class)
    ->weeklyOn(1, '7:40');
Schedule::command(GetTrustedCertsCommand::class)
    ->weeklyOn(1, '8:00');
Schedule::command(GetPrivateKeysCommand::class)
    ->weeklyOn(1, '8:30');
Schedule::command(GetCertificateNotificationCommand::class)
    ->weeklyOn(1, '9:00');
