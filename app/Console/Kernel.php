<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SimulateAQIData::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $settings = \App\Models\SimulationSetting::first();
        if ($settings && $settings->is_running) {
            $schedule->command('sensors:update-readings')
                     ->everySecond()
                     ->when(function () use ($settings) {
                         return now()->second % ($settings->frequency ?? 60) === 0;
                     });
        }
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
