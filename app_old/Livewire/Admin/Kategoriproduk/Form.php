<?php

namespace App\Livewire\Admin\Kategoriproduk;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\ProductCategoryModel;
use App\Models\RequestUpdateModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{

    public $category;
    public $descriptions;
    public $editform = false;
    public $lm;

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_KATEGORI_PRODUK)){
            return ;
        }
        $this->lm = ProductCategoryModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->category = $this->lm?->category ?? '';
        $this->descriptions = $this->lm?->descriptions ?? '';
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        $v =   $this->validate([
            'category' => 'required|min:4',
        ],[
            'category' => [
                'required' => 'Kategori Produk harus diisikan',
                'min' => 'Nama kategori minimal 4 karakter'
            ],
        ]);
        $v['descriptions'] = $this->descriptions;
        return $v;
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_KATEGORI_PRODUK)){
            return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $m = ProductCategoryModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->edit(0);
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_KATEGORI_PRODUK, 'Tambah Kategori Produk ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_KATEGORI_PRODUK)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $m = ProductCategoryModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_KATEGORI_PRODUK, 'Ubah Kategori Produk ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.kategoriproduk.form');
    }
}
