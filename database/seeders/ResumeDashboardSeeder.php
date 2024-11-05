<?php

namespace Database\Seeders;

use App\Jobs\ResumeDashboarJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResumeDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResumeDashboarJob::dispatch()->onConnection('sync');
    }
}
