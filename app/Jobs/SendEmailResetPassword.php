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
        if ($this->attempt >= 3) {
            \Log::warning("Max retry attempts reached for email: {$this->mail}");
            return;
        }

        try {
            \Log::info("Attempting to send reset password email to: {$this->mail}, Attempt: {$this->attempt}");

            // Check if email exists
            $cek = DB::table('users')->where('email', $this->mail)->first();
            if (!$cek) {
                \Log::error("Email not found in users table: {$this->mail}");
                return;
            }

            // Generate token and update user data
            $randomToken = Str::random(40);
            $resetLink = url('/reset-password-akun/' . $randomToken);
            $data = [
                'token_reset' => $randomToken,
            ];

            $simpan = UserModel::query()->where('email', $cek->email)->update($data);
            if ($simpan) {
                \Log::info("Token generated and saved for email: {$this->mail}");
                $email = $this->mail;
                // Send email
                Mail::send('frontend.emails.reset-password', [
                    'resetLink' => $resetLink,
                    'user' => $cek
                ], function (Message $msg) use ($email) {
                    $msg->to($email);
                    $msg->subject('Reset Sandi');
                });

                \Log::info("Reset password email successfully sent to: {$this->mail}");
            } else {
                \Log::error("Failed to save token for email: {$this->mail}");
            }
        } catch (\Exception $e) {
            \Log::error("Error while sending reset password email to: {$this->mail}, Attempt: {$this->attempt}, Error: {$e->getMessage()}");
            SendEmailResetPassword::dispatch($this->mail, $this->attempt + 1);
        }
    }
}
