<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('gateway:services')->weeklyOn(1,"6:30");
        $schedule->command('gateway:users')->weeklyOn(1,"6:40");
        $schedule->command('certificates:check')->weeklyOn(1,"8:00");
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
