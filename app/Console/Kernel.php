<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command("eve:esi:status")->hourly();
        $schedule->command('eve:universe:stats')->hourly();
        $schedule->command('eve:universe:cleanup')->hourly();
        $schedule->command("eve:server:status")->everyMinute();
        $schedule->command('eve:esi:status')->everyMinute();

    }

    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $shortSchedule)
    {
        $shortSchedule->command('eve:location:location')
            ->everySeconds(5)
            ->withoutOverlapping();

        $shortSchedule->command('eve:location:online')
            ->everyseconds(30)
            ->withoutOverlapping();

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
