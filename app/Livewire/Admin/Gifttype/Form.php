<?php

namespace App\Livewire\Admin\Gifttype;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\GiftTypeModel;
use App\Models\LevelMemberModel;
use App\Models\ProductCategoryModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    public $name;
    public $price;
    public $description;
    public $level_member_id;
    public $editform = false;
    public $lm;
    public $levels;

    public function mount(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_LEVEL_MEMBER)){
            return ;
        }
        $this->levels = LevelMemberModel::get();
    }

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_JENIS_HADIAH)){
            return ;
        }


        $this->lm = GiftTypeModel::query()->find($id);
        $this->editform = $this->lm != null;
        $this->level_member_id = $this->lm?->level_member_id ?? '';
        $this->name = $this->lm?->name ?? '';
        $this->price = $this->lm?->price ?? 0;
        $this->description = $this->lm?->description ?? '';
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        $v =   $this->validate([
            'name' => 'required|min:4',
            'price' => 'required|numeric|min:0',
            'level_member_id' => 'required',
        ],[
            'name' => [
                'required' => 'Nama gift harus diisikan',
                'min' => 'Nama gif minimal 4 karakter'
            ],
            'price' => 'Harga harus ditentukan, minimal 0',
            'level_member_id' => "Level harus dipilih"
        ]);
        $v['description'] = $this->description;
        return $v;
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_JENIS_HADIAH)){
            return ;
        }


        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $m = GiftTypeModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->edit(0);
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_JENIS_HADIAH, 'Tambah data Jenis hadiah', $v);
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
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_JENIS_HADIAH)){
            return ;
        }


        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $m = GiftTypeModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_JENIS_HADIAH, 'Ubah data Jenis hadiah', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.gifttype.form');
    }
}
