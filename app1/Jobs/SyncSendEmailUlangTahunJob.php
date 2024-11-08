<?php

namespace App\Jobs;

use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SyncSendEmailUlangTahunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = Carbon::now()->format('m-d');
        $member = UserModel::query()->whereRaw("DATE_FORMAT(birth_at, '%m-%d') = ?", [$today])->get();
        foreach ($member as $item) {
            try{
                Mail::send('admin.email.birthday', ['user' => $item], function (Message $message) use ($item) {
                    $message->to($item->email, $item->first_name . ' ' . $item->last_name)
                            ->subject('Selamat Ulang Tahun '.$item->first_name . ' ' . $item->last_name);
                });
            }catch (\Exception $e){}
        }
    }
}
