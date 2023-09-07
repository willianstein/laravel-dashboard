<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // $schedule->command('inspire')->hourly();
        $schedule->command('orderintegrations:run')
            ->everyThreeMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/orderintegrations/log-'.date('Y-m-d').'.log'));

        $schedule->command('nfintegrations:run')
            ->everyThreeMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/nfintegrations/log-'.date('Y-m-d').'.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
