<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('app:cancel-expired-vnpay-orders')->everyFiveMinutes();

        // $schedule->command('orders:send-reminders')->hourly(); // Hoặc ->cron('0 * * * *') để chạy vào phút 00 của mỗi giờ
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
