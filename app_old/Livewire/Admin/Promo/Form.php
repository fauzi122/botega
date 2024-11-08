<?php

namespace App\Livewire\Admin\Promo;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\PromoModel;
use App\Models\RequestUpdateModel;
use Livewire\Component;

class Form extends Component
{

    protected $listeners = ['edit', 'newForm'];
    public $level_member_id;
    public $product_id;
    public $price;
    public $expired_at;
    public $editform = false;
    public $model;
    public $product;
    public $kode;

    public function mount($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PROMO)){
            return ;
        }

        $this->levelmember = LevelMemberModel::get();
        $this->level_member_id = $id;
    }

    public function newForm(){
        $this->edit(0);
    }

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PROMO)){
            return ;
        }

        $this->model = PromoModel::view()->where('id',$id)->first();
        $this->editform = $this->model != null;
        $this->level_member_id = $this->model?->level_member_id;
        $this->product_id = $this->model?->product_id;
        $this->price = $this->model?->price ?? 0;
        $this->expired_at = $this->model?->expired_at;
        $this->product = $this->model?->product;
        $this->kode = $this->model?->kode;

    }

    private function validasi(){
        $v =   $this->validate([
            'level_member_id' => 'required',
            'product_id' => 'required',
            'price' => 'numeric',
        ],[
            'level_member_id' => [
                'required' => 'Tingkatan member harus diisikan',
            ],
            'product_id' => 'Produk harus ditentukan',
            'price' => 'Harga harus berupa angka',
        ]);
        $v['expired_at'] = $this->expired_at;
        return $v;
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_PROMO)){
            return ;
        }

        $v = $this->validasi();

        try {
            $m = PromoModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');

            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_PROMO, 'Menambah data promo', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PROMO)){
            return ;
        }

        $v = $this->validasi();

        try {
            $m = PromoModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');

            LogController::writeLog(ValidatedPermission::UBAH_DATA_PROMO, 'Merubah data Promo', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.promo.form');
    }
}
