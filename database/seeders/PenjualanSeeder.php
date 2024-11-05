<?php

namespace Database\Seeders;

use App\Jobs\SyncPenjualanJob;
use App\Models\DetailTransactionModel;
use App\Models\ProductModel;
use App\Models\ProsesHistoryModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        SyncPenjualanJob::dispatch(Carbon::now()->subYears(3)->format('d/m/Y') )->onConnection('sync');

//        SyncPenjualanJob::dispatch(Carbon::now()->subDays(2)->format('d/m/Y') )->onConnection('sync');
        //SyncPenjualanJob::dispatch()->onConnection('sync');
        SyncPenjualanJob::dispatch('',false,'SOJ/24/07/01613')->onConnection('sync');
//        SyncPenjualanJob::dispatch(Carbon::parse('2024-03-09')->format('d/m/Y') )->onConnection('sync');
//        SyncPenjualanJob::dispatch(Carbon::now()->subMonths(3)->format('d/m/Y') )->onConnection('sync');
 
//        SyncPenjualanJob::dispatch($tgl1, false, '', $tgl1)->onConnection('sync');
 
//        $tgl1 = Carbon::now()->subDays(1)->format('d/m/Y');
//        SyncPenjualanJob::dispatch($tgl1, false, '', $tgl1)->onConnection('sync');
 
    }
}
