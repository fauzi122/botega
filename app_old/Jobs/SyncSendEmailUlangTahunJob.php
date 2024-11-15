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
    $members = UserModel::query()->whereRaw("DATE_FORMAT(birth_at, '%m-%d') = ?", [$today])->get();
    foreach ($members as $member) {
        try {
            Mail::send('admin.email.birthday', ['user' => $member], function (Message $message) use ($member) {
                $message->to($member->email, $member->first_name . ' ' . $member->last_name)
                        ->subject('Selamat Ulang Tahun ' . $member->first_name . ' ' . $member->last_name);
            });
            Log::info('Birthday email sent to: ' . $member->email);
        } catch (\Exception $e) {
            Log::error('Failed to send birthday email to: ' . $member->email . ' Error: ' . $e->getMessage());
        }
    }
    }
}
