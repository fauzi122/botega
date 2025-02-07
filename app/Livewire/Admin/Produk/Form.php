<?php

namespace App\Livewire\Admin\Produk;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\ProductModel;
use App\Models\RequestUpdateModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{

    public $kode;
    public $name;
    public $descriptions;
    public $price;
    public $cost_price;
    public $qty;
    public $category_id;
    public $sales_qty;
    public $users_id;
    public $category;
    public $editform = false;
    public $lm;

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PRODUK)){
            return ;
        }

        $this->lm = ProductModel::view()->where('id',$id)->first();
        $this->editform= $this->lm != null;
        $this->kode = $this->lm?->kode ?? '';
        $this->category = $this->lm?->category ?? '';
        $this->name = $this->lm?->name;
        $this->descriptions = $this->lm?->descriptions ?? '';
        $this->price = (int) $this->lm?->price ?? 0;
        $this->cost_price = (int) $this->lm?->cost_price ?? 0;
        $this->qty = (int)$this->lm?->qty ?? 0;
        $this->category_id = $this->lm?->category_id;
        $this->users_id = $this->lm?->users_id ;
    }

    public function newForm(){
        $this->edit(0);
    }

    public function toggleHomeAktif($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PRODUK)) {
            return;
        }

        ProductModel::where('id', $id)->update(['sts_home' => \DB::raw('not sts_home')]);

    }
    public function productAktif($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PRODUK)) {
            return;
        }

        ProductModel::where('id', $id)->update(['sts_product' => 1]);
    }
    public function toggleProductAktif($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PRODUK)) {
            return;
        }

        ProductModel::where('id', $id)->update(['sts_product' => \DB::raw('not sts_product')]);
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    private function validasi(){
        $v =   $this->validate([
            'kode' => 'required|unique:products,kode,'.$this->lm?->id,
            'name' => 'required',
            'category_id' => 'required'
        ],[
            'kode' => [
                'required' => 'Kode produk harus diisikan',
                'unique' => 'Kode produk harus unik'
            ],
            'name' => 'Nama produk harus diisikan',
            'category_id' => 'Kategori produk harus dipilih',
        ]);
        $v['price'] = (double)$this->price;
        $v['cost_price'] = (double)$this->cost_price;
        $v['qty'] = (double)$this->qty;
        $v['descriptions'] = $this->descriptions;
        return $v;
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_PRODUK)){
            return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $m = ProductModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_PRODUK, 'Tambah data produk ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PRODUK)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            $m = ProductModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_PRODUK, 'Ubah data produk ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.produk.form');
    }
}
