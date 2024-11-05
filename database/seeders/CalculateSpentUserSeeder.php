<?php

namespace Database\Seeders;

use App\Jobs\CalcMemberExpenseJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalculateSpentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CalcMemberExpenseJob::dispatch()->onConnection('sync');
    }
}
