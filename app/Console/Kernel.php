<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command("inspire")->hourly();
        $schedule->command('telegram:message')->cron('0 10,14,18,22 * * 1-5')
            ->before(function () {Log::info('Стартуем');})
            ->after(function () {Log::info('Типо готово');});
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
