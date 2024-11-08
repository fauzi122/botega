<?php

namespace App\Jobs;

use App\Models\LogsModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $isi;
    protected $user_id;
    protected $title;

    public function __construct($isi, $user_id,$title)
    {
        $this->isi = $isi;
        $this->user_id = $user_id;
        $this->title = $title;
    }

    public function handle():void
    {
        try {
            $log = [
                'actions' => $this->title,
                'payload' => json_encode($this->isi),
                'user_id' => $this->user_id,
                'created_at' => Carbon::now('Asia/Jakarta')
            ];

            LogsModel::query()->insert($log);
        } catch (\Exception $e) {
            \Log::error('Error in UpdateProfileJob: ' . $e->getMessage());
        }
    }
}
