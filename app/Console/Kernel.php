<?php

namespace App\Console;

use App\Models\Job;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
        $schedule->call(function () {
            // abgelaufene Jobs anhand vom Datum auf abgelaufen setzen
            Job::where('jobs.end', '<',  DB::raw('curdate()'))->update([
                'status_fk' => 4,
            ]);
        })->daily();

        $schedule->command('storage:link --force');
        $schedule->command('config:clear --force');
        $schedule->command('storage:link --force')->daily();
        $schedule->command('config:clear --force')->daily();
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
