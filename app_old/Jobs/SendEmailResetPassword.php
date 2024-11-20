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

class SendEmailResetPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mail;
    protected  $attempt;
    /**
     * Create a new job instance.
     */
    public function __construct($email = '', $attempt = 0)
    {
        $this->mail = $email;
        $this->attempt = $attempt;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->attempt >= 3)return;

        try {
            $cek =DB::table('users')->where('email', $this->mail)->first();

            $randomToken = Str::random(40);
            $resetLink = url('/reset-password-akun/' . $randomToken);
            $data = [
               'token_reset'=>$randomToken
            ];

            $simpan= UserModel::query()->where('email',$cek->email)->update($data);
            if ($simpan) {
                $email = $this->mail;
                Mail::send('frontend.emails.reset-password', [
                    'resetLink' => $resetLink,
                    'user' => $cek
                ], function (Message $msg) use ($email) {
                    $msg->to($email);
                    $msg->subject('Reset Sandi');
                });
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            SendEmailResetPassword::dispatch($this->mail, $this->attempt+1);
        }
    }
}
