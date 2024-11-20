<?php

namespace App\Livewire\Admin\Kategori;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\ArticleCategoryModel;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use Carbon\Carbon;
use Livewire\Component;

class
Form extends Component
{
    public $category = null;
    public $publish = true;
    public $editform = false;
    public $lm = null;

    public function edit($id) {
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_KATEGORI_ARTIKEL)){
            return ;
        }

        // Replace this with your actual logic to fetch data based on id
        $this->lm = ArticleCategoryModel::query()->find($id);  // Replace 'ModelForCategoryAndPublish' with the appropriate model name
        $this->editform = $this->lm != null;
        $this->category = $this->lm?->category ?? '';
        $this->publish = $this->lm?->publish ?? true;
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        return  $this->validate([
            'category' => 'required|string|max:100|unique:article_categories,category,'.$this->lm?->id,
            'publish' => 'required|boolean'
        ],[
            'category' => [
                'required' => 'Kategori harus diisikan',
                'string' => 'Kategori harus berupa string',
                'max' => 'Kategori maksimal 100 karakter',
                'unique' => 'Nama kategori sudah ada, gunakan nama lain'
            ],
            'publish' => 'Publish harus diisikan'
        ]);
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    private function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_KATEGORI_ARTIKEL)){
            return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $m = ArticleCategoryModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
            $this->edit(0);

            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_KATEGORI_ARTIKEL, 'Tambah Kategori Artikel', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    private function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_KATEGORI_ARTIKEL)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            $m = ArticleCategoryModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            $this->edit(0);
            LogController::writeLog(ValidatedPermission::UBAH_DATA_KATEGORI_ARTIKEL, 'Ubah Kategori Artikel', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function togglePublish($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_KATEGORI_ARTIKEL)){
            return ;
        }

        $p = ArticleCategoryModel::find($id);
        if($p == null)return;
        $p->publish = $p->publish == 1 ? 0 : 1;
        $p->save();
        LogController::writeLog(ValidatedPermission::UBAH_DATA_KATEGORI_ARTIKEL, 'Mengganti status publish Artikel', $id);
    }

    public function render()
    {
        return view('livewire.admin.kategori.form');
    }
}
