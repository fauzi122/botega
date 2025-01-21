<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailAktivasi;
use App\Jobs\SendEmailResetPassword;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Login extends Controller
{
    //
    public function index()
    {
        //        var_dump(Hash::make(12345));die();
        return view('frontend.auth.login');
    }


    public function validasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $username = $request->input('email');
        $password = $request->input('password');

        $cek = DB::table('users')->where('email', $username)->where('reward_type', '2')->first();
        if ($cek) {
            try {
                if (Hash::check($password, $cek->pass)) {
                    if ($cek->date_verify_email == null || $cek->date_verify_email == '0000-00-00 00:00:00') {
                        return redirect()->back()->with('error', 'Mohon maaf akun anda belum diaktivasi melalui email terdaftar');
                    }
                    Session::put('user', $cek);
                    return redirect('/home')->with('success', 'Selamat Datang, ' . $cek->first_name . ' ' . $cek->last_name);
                } else {
                    dd("1");

                    return redirect()->back()->with('error', 'Username dan Password Salah');
                }
            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'Username dan Password Salah');
            }
        } else {

            return redirect()->back()->with('error', 'Member Tidak Ditemukan ');
        }
    }

    public function register()
    {
        return view('frontend.auth.register');
    }



    public function registeracc(Request $request)
    {
        // Periksa apakah password dan konfirmasi password sesuai
        if ($request->password !== $request->confirm_password) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak sesuai');
        }

        $cek = UserModel::query()->where('email', $request->email)->count();
        if ($cek > 0) {
            return redirect()->back()->with('error', 'Email sudah pernah terdaftar pada sistem');
        }
        $randomToken = Str::random(40);
        $resetLink = url('/aktivasi-akun/' . $randomToken);

        $prefix = 'WE.';
        $length = 4;

        $lastId = UserModel::orderBy('id_no', 'desc')->value('id_no');
        $lastId = $lastId ? intval(substr($lastId, strlen($prefix))) : 0;
        $idmember = $prefix . sprintf('%0' . $length . 'd', $lastId + 1);

        $datainsert = [
            'id_no' => $idmember,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'hp' => $request->nohp,
            'pass' => Hash::make($request->password),
            'code_verify_email' => $randomToken,
        ];

        $email = $request->email;
        $test = Mail::send('frontend.emails.aktivasi', [
            'activationLink' => $resetLink,
            'email' => $email
        ], function (Message $msg) use ($email) {
            $msg->to($email);
            $msg->subject('Aktivasi Akun');
        });

        if (!$test) {
            return redirect()->back()->with('error', 'Gagal mengirim email.');
        }
        UserModel::query()->insert($datainsert);

        return redirect('login/register')->with('success', 'Silahkan aktivasi akun anda melalui email');
    }

    public function forget()
    {
        return view('frontend.auth.forget');
    }
    public function forgetacc(Request $request)
    {
        $email = $request->input('email');
        $cek = DB::table('users')->where('email', $email)->first();

        if ($cek) {
            SendEmailResetPassword::dispatch($email)->onConnection('sync');
            //            $randomToken = Str::random(40);
            //            $resetLink = url('/reset-password-akun/' . $randomToken);
            //            $data = [
            //                'token_reset'=>$randomToken
            //            ];
            //            $simpan= UserModel::query()->where('email',$email)->update($data);
            //            if ($simpan) {
            //
            //                Mail::send('frontend.emails.reset-password', [
            //                    'resetLink' => $resetLink,
            //                    'user' => $cek,
            //                    'email' => $email
            //                ], function (Message $msg) use ($email) {
            //                    $msg->to($email);
            //                    $msg->subject('Reset Sandi');
            //                });
            //            }
            Session::flash('success', 'Reset password sudah dikirim ke email yaa :)');
            return redirect()->intended('login/forget');
        } else {
            Session::flash('error', 'Mohon maaf email tidak terdaftar disistem :(');
            return redirect()->intended('login/forget');
        }
    }

    public function aktivasiakun($token)
    {
        $cek = UserModel::query()
            ->where('code_verify_email', $token)
            ->where('date_verify_email', null)
            ->first();

        if ($cek) {
            $data = [
                'date_verify_email' => Carbon::now()
            ];
            $tes = UserModel::query()->where('code_verify_email', $cek->code_verify_email)->update($data);


            return redirect('login')->with('success', 'Aktivasi Akun sudah berhasil');
        } else {
            return redirect('login')->with('error', 'Sudah melakukan aktivasi akun atau Token expired');
        }
    }

    public function  resetpasswordakun($token)
    {
        $cek = UserModel::query()
            ->where('token_reset', $token)
            ->first();
        // dd($cek);
        if ($cek) {
            return view('frontend.auth.forget_pass', compact('token'));
        } else {
            return redirect('login')->with('error', 'Sudah melakukan aktivasi akun atau Token expired');
        }
    }

    public function resetpasswordacc(Request $request)
    {
        $pass = $request->password;
        $pass_conf = $request->confirm_password;
        $token = $request->token;
        // dd($pass . '/' . $pass_conf . '/' . $token);
        if ($pass <> $pass_conf) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak sessuai');
        }
        $cek = UserModel::query()->where('token_reset', $token)->first();
        if ($cek == null) {
            return redirect('login')->with('error', 'Sudah melakukan aktivasi akun atau Token expired');
        }

        $data = [
            'pass' => Hash::make($pass),
            'token_reset' => null,
            "date_verify_email" =>  $cek?->date_verify_email ?? Carbon::now()
        ];

        //        $cek = UserModel::query()->where('token_reset',$token)->update($data);
        $c = DB::update(
            'UPDATE users SET token_reset = null, pass = ?, date_verify_email=? WHERE token_reset = ?',
            [Hash::make($pass), $cek?->date_verify_email ?? Carbon::now(), $token]
        );
        //        var_dump($cek);die();
        if ($c > 0) {
            return redirect('login')->with('success', 'Password berhasil diupdate. Silahkan login menggunakan password baru');
        } else {
            return redirect('login')->with('error', 'Password gagal diupdate');
        }
    }

    public function ubahpassword()
    {
        return view('frontend.auth.pass_lama');
    }

    public function resetpasslama(Request $request)
    {
        $pass = $request->password;
        $pass_conf = $request->confirm_password;
        $password_lama = $request->password_lama;
        $id = session('user')?->id;

        if ($pass <> $pass_conf) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak sessuai');
        }
        $cek = UserModel::query()->where('id', $id)->first();

        if ($cek) {
            if (Hash::check($password_lama, $cek->pass)) {
                $data = [
                    'pass' => Hash::make($pass),
                ];
                UserModel::query()->where('id', $id)->update($data);
                return redirect()->back()->with('success', 'Password berhasil diupdate');
            } else {

                return redirect()->back()->with('error', 'Username dan Password Salah');
            }
        } else {

            return redirect()->back()->with('error', 'Username dan Password Salah');
        }
    }

    public function logout()
    {
        \session()->flush();
        return redirect()->to(URL::previous());
    }
}
