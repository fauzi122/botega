<?php

namespace Database\Seeders;

use App\Jobs\SyncSaleReturnJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SyncSaleReturnJob::dispatch()->onConnection('sync');
    }
}
