<?php

namespace App\Jobs;

use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendEmailAktivasi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mail;
    /**
     * Create a new job instance.
     */
    public function __construct($email = '')
    {
        $this->mail = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $cek =DB::table('users')->where('email', $this->mail)->first();
            $randomToken = Str::random(40);
            $resetLink = url('/aktivasi-akun/' . $randomToken);
            $data = [
                'code_verify_email'=>$randomToken
            ];

            $simpan= UserModel::query()->where('email',$cek->email)->update($data);
            if ($simpan) {
                $email = $this->mail;
                Mail::send('frontend.emails.aktivasi', [
                    'resetLink' => $resetLink
                ], function (Message $msg) use ($email) {
                    $msg->to($email);
                    $msg->subject('Aktivasi Akun');
                });
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }
}
