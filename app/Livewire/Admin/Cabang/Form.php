<?php

namespace App\Livewire\Admin\Cabang;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\CabangModel;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use Livewire\Component;

class Form extends Component
{

    protected $listeners = ['edit', 'newForm'];

    public $nama;
    public $aktif;
    public $editform = false;
    public $lm;

    public function edit($id){
        if(!ValidatedPermission::authorize('Cabang.Read')){
            return;
        }

        $this->lm = CabangModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->nama = $this->lm?->nama ?? '';
        $this->aktif = $this->lm?->aktif ?? 0;
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){


        return   $this->validate([
            'nama' => 'required|min:3',
        ],[
            'nama' => [
                'required' => 'Nama cabang harus diisikan',
                'min' => 'Nama cabang minimal 3 karakter'
            ],
            'aktif' => 'Status harus diisikan'
        ]);
    }

    public function store(){
        if(!ValidatedPermission::authorize('Cabang.Store')){
            return;
        }

        $v = $this->validasi();
        $v['aktif'] = $this->aktif;

        try {
            $m = CabangModel::query()->insert($v);
            $this->nama = '';
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');

            LogController::writeLog(ValidatedPermission::UBAH_DATA_CABANG, 'Merubah data cabang', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function toggleAktif($id){
        if(!ValidatedPermission::authorize('Cabang.Update')){
            return;
        }

        CabangModel::where('id', $id)->update(['aktif'=>\DB::raw('not aktif')]);

        LogController::writeLog(ValidatedPermission::UBAH_DATA_CABANG, 'Merubah status aktif cabang', $id);

    }

    public function update(){
        if(!ValidatedPermission::authorize('Cabang.Update')){
            return;
        }

        $v = $this->validasi();
        $v['aktif'] = $this->aktif;

        try {
            $m = CabangModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_CABANG, 'Merubah data cabang', $v);

        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.cabang.form');
    }
}
