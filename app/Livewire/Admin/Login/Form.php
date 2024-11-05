<?php

namespace App\Livewire\Admin\Login;

use App\Jobs\SendEmailResetPassword;
use App\Models\UserModel;
use Livewire\Component;

class Form extends Component
{
    public $username;
    public $password;
    public $email;
    public $isforgot = false;
    public $showpassword = false;

    public function render()
    {
        return view('livewire.admin.login.form');
    }

    private function validasi(){
        $v = $this->validate([
            "username" => "required",
            "password" => "required"
        ],[
            "username" => "Nama Pengguna harus diisi",
            "password" => "Kata sandi harus diisikan"
        ]);
        return $v;
    }

    public function login(){
        session()->remove('error');

        $v = $this->validasi();
        $r = UserModel::view()->where("user_type", "admin")
                ->where("email", $v["username"])->first();
        if($r == null){
            session()->flash("error", "Identitas Pengguna tidak terdaftar");
            return false;
        }

        if(($r->pass ?? '') == ''){
            session()->flash("error", "Katasandi akun belum di konfigurasi");
            return false;
        }

        try {
            if (\Hash::check($v['password'], $r->pass)) {
                session()->put("admin", $r);
                return redirect(url('/admin/dashboard'));
            } else {
                session()->flash("error", "Kombinasi  Identitas Pengguna dan Sandi tidak benar.");
            }
        }catch (\Exception $e){
            session()->flash("error", "Kesalahan Katasandi yang dimiliki, harap meminta token reset sandi kepada admin.");
        }
    }

    public function forgot(){
        $user = UserModel::query()->where([
            'user_type' => 'admin',
            'email' => $this->email
        ])->first();

        if($user == null){

            session()->flash('error', 'Email tidak dikenali');

        }else{
            SendEmailResetPassword::dispatch($this->email);
            session()->flash('success', 'Token reset telah dikirim ke email');
        }
    }

    public function showForgot(){
        $this->isforgot = true;

    }

    public function loginform(){
        $this->isforgot = false;
    }

    public function togglePassword(){

    }
}
