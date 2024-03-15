<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('gateway:services')
    ->weeklyOn(1,"6:30");
Schedule::command('gateway:users')
    ->weeklyOn(1,"6:40");
Schedule::command('certificates:check')
    ->weeklyOn(1,"8:00");

