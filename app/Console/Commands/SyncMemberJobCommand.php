<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncMemberJob;

class SyncMemberJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-member-job-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncMemberJob::dispatch(1); // Mode 1 atau lainnya
    }
}
