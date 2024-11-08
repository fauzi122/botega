<?php

namespace App\Livewire\Admin\Hakakses;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use App\Models\RoleAccessRightModel;
use App\Models\RoleModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    public $name;
    public $descriptions;
    public $editform = false;
    public $lm;

    public function edit($id){

        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_HAK_AKSES)){
            return ;
        }

        $this->lm = RoleModel::query()->find($id);
        $this->editform = $this->lm != null;
        $this->name = $this->lm?->name ?? '';
        $this->descriptions = $this->lm?->descriptions;
    }

    private function validasi(){
        return $this->validate([
            'name' => 'required|min:4',
            'descriptions' => 'required',
        ],[
            'name.required' => 'Nama Peran Akses harus diisikan',
            'name.min' => 'Nama Peran Akses minimal 4 karakter',
            'descriptions.required' => 'Deskripsi harus diisikan',
        ]);
    }

    public function newForm(){
        $this->edit(0);
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_HAK_AKSES)){
          return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $id = RoleModel::query()->insertGetId($v);
            RoleAccessRightModel::buatHakAkses($id);
            $this->edit($id);
            session()->flash('success', 'Data berhasil di simpan ');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_HAK_AKSES, 'Tambah Hak Akses ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }



    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_HAK_AKSES)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            RoleModel::query()->where('id', $this->lm->id)->update($v);
            $this->edit($this->lm->id);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_HAK_AKSES, 'Ubah Peran Akses  ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.hakakses.form');
    }
}
