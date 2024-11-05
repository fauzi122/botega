<?php

namespace Database\Seeders;

use App\Jobs\SyncMemberJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SyncMember extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SyncMemberJob::dispatch(3)->onConnection('sync');

    }
}
