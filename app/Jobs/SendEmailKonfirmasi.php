<?php

namespace App\Jobs;

use App\Models\EventsModel;
use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendEmailKonfirmasi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $member_levels;
    protected $event_id;

    /**
     * Create a new job instance.
     */
    public function __construct($member_levels = [], $event_id = 0)
    {
        $this->member_levels = $member_levels;
        $this->event_id = $event_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Job SendEmailKonfirmasi dimulai');

        try {
            Log::info('Data member ditemukan: ' . $this->member_levels);
            Log::info('Data member event: ' . $this->event_id);

            $decode = json_decode($this->member_levels);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error decoding JSON: ' . json_last_error_msg());
            }
            Log::info('Data member decode: ' . implode(', ', $decode));

            $users = DB::table('users')
                ->whereIn('level_member_id', $decode)
//                ->limit(2)
                ->get();

            Log::info('Jumlah users ditemukan: ' . $users->count());

            foreach ($users as $user) {
                if (empty($user->email)) continue;

                $randomToken = Crypt::encrypt($this->event_id . 'k0nf1m4' . $user->id);
                $resetLink = url('/konfirmasi-hadir-event/' . $randomToken);

                $event = EventsModel::query()->where('id',$this->event_id)->first();

                Mail::send('frontend.emails.konfirmasi', [
                    'judul' => $event->judul,
                    'start' => $event->start,
                    'end' => $event->end,
                    'descriptions' => $event->descriptions,
                    'user' => $user->first_name . ' ' . $user->last_name,
                    'resetLink' => $resetLink
                ], function (Message $message) use ($user) {
                    $message->to($user->email)
                        ->subject('Konfirmasi Kehadiran');
                });

                Log::info('Email sent to: ' . $user->email);
            }

        } catch (\Exception $e) {
            Log::error('Error in SendEmailKonfirmasi job: ' . $e->getMessage());
        }

        Log::info('Job SendEmailKonfirmasi selesai');
    }
}
