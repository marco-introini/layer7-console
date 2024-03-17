<?php

use App\Console\Commands\CheckCertificatesCommand;
use App\Console\Commands\GetGatewayUsersCommand;
use App\Console\Commands\GetPrivateKeysCommand;
use App\Console\Commands\GetServicesCommand;
use App\Console\Commands\GetTrustedCertsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(GetServicesCommand::class)
    ->weeklyOn(1, '7:30');
Schedule::command(GetGatewayUsersCommand::class)
    ->weeklyOn(1, '7:40');
Schedule::command(GetTrustedCertsCommand::class)
    ->weeklyOn(1, '8:00');
Schedule::command(GetPrivateKeysCommand::class)
    ->weeklyOn(1, '8:30');
Schedule::command(CheckCertificatesCommand::class)
    ->weeklyOn(1, '9:00');
