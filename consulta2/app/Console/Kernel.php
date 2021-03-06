<?php

namespace App\Console;

use App\Console\Commands\absoluteClear;
use App\Console\Commands\autoAssignOverTreatment;
use App\Console\Commands\debtResume;
use App\Console\Commands\sendReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        absoluteClear::class,
        sendReminder::class,
        autoAssignOverTreatment::class,
        debtResume::class,
        absoluteClear::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('consulta2:sendsimplereminder')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}