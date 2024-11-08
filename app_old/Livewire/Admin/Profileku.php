<?php

namespace App\Livewire\Admin;

use App\Jobs\SendEmailResetPassword;
use App\Models\UserModel;
use App\Rules\CustomImageBase64Rule;
use Livewire\Component;

class Profileku extends Component
{


    public $id;
    public $first_name;
    public $last_name;
    public $gender;
    public $birth_at;
    public $home_addr;
    public $phone;
    public $email;
    public $hp;
    public $wa;
    public $file;
    public $foto_path;
    public $editform = false;
    public $userModel;


    public function showdata(){
        $id = session('admin')?->id;
        $this->userModel = UserModel::query()->find($id);
        $this->editform = $this->userModel !== null;

        $this->id = $this->userModel?->id ?? '';
        $this->first_name = $this->userModel?->first_name ?? '';
        $this->last_name = $this->userModel?->last_name ?? '';
        $this->gender = $this->userModel?->gender ?? '';
        $this->birth_at = $this->userModel?->birth_at ?? '';
        $this->home_addr = $this->userModel?->home_addr ?? '';
        $this->phone = $this->userModel?->phone ?? '';
        $this->email = $this->userModel?->email ?? '';
        $this->hp = $this->userModel?->hp ?? '';
        $this->wa = $this->userModel?->wa ?? '';
        $this->foto_path = $this->userModel?->foto_path ?? '-';
        $this->file = '';
        $this->foto_path = \Storage::exists($this->foto_path) ? url('admin/pengguna/photo/'.$this->id).'.png' : null;


    }


    public function save(){
        $this->update();

    }

    private function validasi(){

        $v = $this->validate([
            'first_name' => 'required|min:4',
            'last_name' => 'required|min:4',
            'gender' => 'required',
            'email' => 'required|email',
            'file' => strlen($this->file) > 1024 ? new CustomImageBase64Rule() : ''
        ], [
            'first_name.required' => 'Nama depan harus diisikan',
            'first_name.min' => 'Nama depan minimal 4 karakter',
            'last_name.required' => 'Nama belakang harus diisikan',
            'last_name.min' => 'Nama belakang minimal 4 karakter',
            'gender.required' => 'Gender harus diisikan',
            'email.required' => 'Email harus diisikan',
            'email.email' => 'Format email tidak valid',
        ]);
        unset($v['file']);
        $v['user_type'] = 'admin';
        $v['birth_at'] = $this->birth_at;
        $v['home_addr'] = $this->home_addr;
        $v['phone'] = $this->phone;
        $v['hp'] = $this->hp;
        $v['wa'] = $this->wa;
        return $v;
    }

    public function hapusgambar($id){
        $u = UserModel::where('user_type', 'admin')->find($id);
        if($u==null)return;
        if(\Storage::exists($u->foto_path ?? '')){
            \Storage::delete($u->foto_path ?? '') ;
        }
    }

    public function update(){
        $v = $this->validasi();

        try {
            $id = $this->userModel->id;
            $m = UserModel::query()->where('id', $id )->update($v);
            $dec = decodeBase64Image($this->file);
            if( $dec !== false ){
                $path = 'pengguna/'.$id.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = UserModel::find($id);
                    $arc->foto_path = $path;
                    $arc->save();
                }
            }
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function resetSandi(){
        SendEmailResetPassword::dispatch($this->email);
    }

    public function render()
    {
        return view('livewire.admin.profileku');
    }
}
