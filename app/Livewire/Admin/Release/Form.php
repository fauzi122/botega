<?php

namespace App\Livewire\Admin\Release;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\ReleaseNote;
use Livewire\Component;
use PHPUnit\Exception;

class Form extends Component
{

    protected $listeners = ['edit', 'newForm'];

    public $tipe;
    public $judul;
    public $deskripsi;
    public $editform = false;
    public $lm;

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_REWARD)){
            return ;
        }

        $this->lm = ReleaseNote::query()->find($id);
        $this->editform= $this->lm != null;
        $this->kode = $this->lm?->kode ?? '';
        $this->judul = $this->lm?->judul ?? '';
        $this->deskripsi = $this->lm?->deskripsi ?? '';
        $this->tipe = $this->lm?->tipe ?? '';
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        $v = $this->validate([
            'judul' => 'required',
            'tipe' => 'required',
            'deskripsi' => 'required',
        ], [
            'judul.required' => 'Judul harus diisikan',
            'tipe.required' => 'Tipe harus diisikan',
            'deskripsi.required' => 'Deskripsi harus diisikan',
        ]);


        return $v;
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }
    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_REWARD)){
            return ;
        }

        $v = $this->validasi();

        try {
            ReleaseNote::create($v);

            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_REWARD, 'Tambah data Release Note', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_REWARD)){
            return ;
        }

        $v = $this->validasi();

        try {
            $lastid = $this->lm?->id;
            ReleaseNote::query()->where('id', $lastid)->update($v);

            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_REWARD, 'Ubah data Release Note', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.release.form');
    }
}
