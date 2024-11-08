<?php

namespace App\Livewire\Admin\Pengguna;

use App\Http\Controllers\Admin\LogController;
use App\Jobs\SendEmailResetPassword;
use App\Library\ValidatedPermission;
use App\Models\ArticleModel;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use App\Models\UserModel;
use App\Rules\CustomImageBase64Rule;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
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
    public $role_id;

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PENGGUNA)){
            return ;
        }

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
        $this->role_id = $this->userModel?->role_id;
        $this->foto_path = $this->userModel?->foto_path ?? '-';
        $this->file = '';
        $this->foto_path = \Storage::exists($this->foto_path) ? url('admin/pengguna/photo/'.$this->id).'.png' : null;


    }

    public function newForm(){

        $this->edit(0);
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    private function validasi(){

        $v = $this->validate([
            'first_name' => 'required|min:4',
            'last_name' => 'required|min:4',
            'gender' => 'required',
            'email' => 'required|email',
            'role_id' => 'required',
            'file' => strlen($this->file) > 1024 ? new CustomImageBase64Rule() : ''
            ], [
            'first_name.required' => 'Nama depan harus diisikan',
            'first_name.min' => 'Nama depan minimal 4 karakter',
            'last_name.required' => 'Nama belakang harus diisikan',
            'last_name.min' => 'Nama belakang minimal 4 karakter',
            'gender.required' => 'Gender harus diisikan',
            'email.required' => 'Email harus diisikan',
            'email.email' => 'Format email tidak valid',
            'role_id.required' => 'Peran akses pengguna harus ditentukan'
        ]);
        unset($v['file']);
        $v['user_type'] = 'admin';
        $v['birth_at'] = strlen($this->birth_at) <10 ? null : Carbon::parse( $this->birth_at )?->format('Y-m-d');

        $v['home_addr'] = $this->home_addr;
        $v['phone'] = $this->phone;
        $v['hp'] = $this->hp;
        $v['wa'] = $this->wa;
        return $v;
    }

    public function hapusgambar($id){

        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PENGGUNA)){
            return ;
        }

        $u = UserModel::where('user_type', 'admin')->find($id);
        if($u==null)return;
        if(\Storage::exists($u->foto_path)){
            \Storage::delete($u->foto_path) ;
            LogController::writeLog(ValidatedPermission::UBAH_DATA_PENGGUNA, 'Hapus Foto Pengguna  ', $id);
        }
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_PENGGUNA)){
            return ;
        }


        $v = $this->validasi();

        try {
            $lastid = UserModel::query()->insertGetId($v);
            $dec = decodeBase64Image($this->file);
            if( $dec !== false ){
                $path = 'pengguna/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = UserModel::find($lastid);
                    $arc->foto_path = $path;
                    $arc->save();
                }
            }

            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_PENGGUNA, 'Tambah data Pengguna  ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PENGGUNA)){
            return ;
        }


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
            $this->edit($id);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_PENGGUNA, 'Ubah data Pengguna  ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function prepareReset($id){
        $this->edit($id);
    }

    public function resetSandi(){
        SendEmailResetPassword::dispatch($this->email);
        LogController::writeLog(ValidatedPermission::UBAH_DATA_PENGGUNA, 'Reset kata sandi pengguna  ', [
            'id' => $this->userModel->id,
            'email' => $this->email,
            'nama' => $this->first_name . ' ' . $this->last_name
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pengguna.form');
    }
}
