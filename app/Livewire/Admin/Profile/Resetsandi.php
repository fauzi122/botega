<?php

namespace App\Livewire\Admin\Profile;

use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\Concerns\Has;
use Livewire\Component;

class Resetsandi extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function resetPassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:5|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi sandi baru tidak sesuai.',
        ]);

        // Logika reset sandi di sini
        $user = UserModel::where('id', session('admin')->id)->first();

        if(\Hash::check($this->current_password, $user->pass)){
            $user->pass = \Hash::make($this->new_password);
            $user->updated_at = Carbon::now();
            $user->save();

            $this->current_password = '';
            $this->new_password = '';
            $this->new_password_confirmation = '';

            session()->flash('success', 'Sandi berhasil di ganti.');
        }else{
            session()->flash('error', 'Sandi lama tidak benar '.$this->current_password.' ' . $user->pass);
        }

        // Setelah berhasil mereset, reset nilai input


    }

    public function render()
    {
        return view('livewire.admin.profile.resetsandi');
    }
}
