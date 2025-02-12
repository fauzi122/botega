<?php

namespace App\Console;

use App\Jobs\CalcMemberExpenseJob;
use App\Jobs\ResetPointPenjualangJob;
use App\Jobs\ResumeDashboarJob;
use App\Jobs\SyncMemberJob;
use App\Jobs\SyncNoFakturFeeMemberJob;
use App\Jobs\SyncPenjualanJob;
use App\Jobs\SyncSaleReturnJob;
use App\Jobs\SendDownPaymentJob;
use App\Jobs\SyncSendEmailUlangTahunJob;
use App\Jobs\ManagePointsJob;
use Carbon\Carbon;
use Dotenv\Dotenv;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // if (env('APP_DEBUG') == 1) {
        //     return;
        // }
        $schedule->call(function () {
            ManagePointsJob::dispatch('update');
        })->dailyAt('19:00')->timezone('Asia/Jakarta');

        $schedule->call(function () {
            // CalcMemberExpenseJob::dispatch()->onConnection('sync');
            CalcMemberExpenseJob::dispatch(0);
        })->dailyAt('20:40')->timezone('Asia/Jakarta');

        // $schedule->call(function () {
        //     // CalcMemberExpenseJob::dispatch()->onConnection('sync');
        //     CalcMemberExpenseJob::dispatch(1);
        // })->dailyAt('11:30')->timezone('Asia/Jakarta');


        $schedule->call(function () {
            // SyncNoFakturFeeMemberJob::dispatch()->onConnection('sync');
            SyncNoFakturFeeMemberJob::dispatch();
        })->everyThreeHours()->timezone('Asia/Jakarta');

        $schedule->call(function () {
            SyncMemberJob::dispatch(1);
            SyncMemberJob::dispatch(2);
            SyncMemberJob::dispatch(3);
        })->dailyAt('01:00')->timezone('Asia/Jakarta');


        $schedule->call(function () {
            // SyncMemberJob::dispatch(0)->onConnection('sync');
            SyncMemberJob::dispatch(0);
        })->dailyAt('11:00')->timezone('Asia/Jakarta');

        $schedule->call(function () {
            $tgl1 = Carbon::now()->subDays(14)->format('d/m/Y');
            $tgl2 = Carbon::now()->format('d/m/Y');

            SendDownPaymentJob::dispatch($tgl1, $tgl2, null, null);
        })->dailyAt('20:00')->timezone('Asia/Jakarta');

        $schedule->call(function () {
            $tgl1 = Carbon::now()->subDays(14)->format('d/m/Y');
            $tgl2 = Carbon::now()->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, true, '', $tgl2);

            // $tgl1 = Carbon::now()->subDays(2)->format('d/m/Y');
            // SyncPenjualanJob::dispatch($tgl1, true, '', $tgl1);

            // $tgl1 = Carbon::now()->subDays()->format('d/m/Y');
            // SyncPenjualanJob::dispatch($tgl1, true, '', $tgl1);
        })->dailyAt('04:00')->timezone('Asia/Jakarta');

        $schedule->call(function () {
            $tgl1 = Carbon::now()->subMonths(7)->format('d/m/Y');
            $tgl2 = Carbon::now()->subMonths(5)->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, false, '', $tgl2);

            $tgl1 = Carbon::now()->subMonths(5)->format('d/m/Y');
            $tgl2 = Carbon::now()->subMonths(4)->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, false, '', $tgl2);

            $tgl1 = Carbon::now()->subMonths(4)->format('d/m/Y');
            $tgl2 = Carbon::now()->subMonths(3)->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, false, '', $tgl2);

            $tgl1 = Carbon::now()->subMonths(3)->format('d/m/Y');
            $tgl2 = Carbon::now()->subMonths(2)->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, false, '', $tgl2);
        })->dailyAt('02:00')->timezone('Asia/Jakarta');

        $schedule->call(function () {

            $tgl1 = Carbon::now()->subMonths(2)->format('d/m/Y');
            $tgl2 = Carbon::now()->subMonths()->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, false, '', $tgl2)
                ->onConnection('sync');

            $tgl1 = Carbon::now()->subMonths()->format('d/m/Y');
            $tgl2 = Carbon::now()->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, false, '', $tgl2)
                ->onConnection('sync');

            SyncPenjualanJob::dispatch('', true, '', '')
                ->onConnection('sync');
        })->dailyAt('11:40')->timezone('Asia/Jakarta');

        $schedule->call(function () {
            SyncSaleReturnJob::dispatch(Carbon::now()->subDays(14)->format('d/m/Y'));
        })->dailyAt('05:00')->timezone('Asia/Jakarta');

        $schedule->call(function () {
            // ResumeDashboarJob::dispatch()->onConnection('sync');
            ResumeDashboarJob::dispatch();
        })->everyFourMinutes()->timezone('Asia/Jakarta');

        $schedule->call(function () {
            ResetPointPenjualangJob::dispatch();
        })->cron("59 23 31 12 *")->timezone('Asia/Jakarta');


        $schedule->call(function () {
            // SyncSendEmailUlangTahunJob::dispatch()->onConnection('sync');
            SyncSendEmailUlangTahunJob::dispatch();
        })->dailyAt('07:30')->timezone('Asia/Jakarta');

        //        $schedule->call(function(){
        //            SyncPenjualanJob::dispatch(Carbon::now()->subDays(60)->format('d/m/Y'), false )->onConnection('sync');
        //        })->dailyAt('00:00');

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
