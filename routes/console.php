<?php

use App\Console\Commands\GetCertificateNotificationCommand;
use App\Console\Commands\GetGatewayUsersInfoCommand;
use App\Console\Commands\GetServicesInfoCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(GetServicesInfoCommand::class)
    ->weeklyOn(1, '6:30');
Schedule::command(GetGatewayUsersInfoCommand::class)
    ->weeklyOn(1, '6:40');
Schedule::command(GetCertificateNotificationCommand::class)
    ->weeklyOn(1, '8:00');
