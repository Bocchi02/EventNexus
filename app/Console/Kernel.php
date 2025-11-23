<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Event;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $now = now();

            // UPCOMING → ONGOING
            Event::where('status', 'upcoming')
                ->where('start_date', '<=', $now)
                ->update(['status' => 'ongoing']);

            // ONGOING → COMPLETED
            Event::where('status', 'ongoing')
                ->where('end_date', '<=', $now)
                ->update(['status' => 'completed']);

        })->everyMinute();
    }


}
