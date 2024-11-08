<?php

namespace App\Livewire\Admin\Produkimage;

use App\Models\ProductCategoryModel;
use App\Models\ProductImageModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $product_id;
    public $name;
    public $path_file;
    public $filefoto;
    public $description;
    public $is_primary = false;
    public $editform = false;
    public $lm;
    public $urlfilefoto;

    public function mount($id){
        $this->product_id = $id;
    }

    public function edit($id){
        $this->lm = ProductImageModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->product_id = $this->lm?->product_id ?? $this->product_id;
        $this->name = $this->lm?->name ?? '';
        $this->is_primary = $this->lm?->is_primary ?? '';
        $this->description = $this->lm?->description ?? '';
        $this->urlfilefoto  = '';
        if(Storage::exists($this->lm?->path_file ?? '--')){
            $this->urlfilefoto = url('admin/produk-image/image/'.$this->lm?->id.'.png');
        }
    }

    public function newForm(){
        $this->edit(0);
    }

    public function delete($id){
        $p = ProductImageModel::query()->where('id',$id)->first();
        if(Storage::exists($p->path_file)){
            Storage::delete($p->path_file);
        }
        ProductImageModel::query()->where('id',$id)->delete();
    }

    private function validasi(){
        $v =   $this->validate([
            'name' => 'required|min:4',
            'filefoto' => $this->editform && $this->urlfilefoto != '' ? '' : 'required|image|max:5000',
        ],[
            'name' => [
                'required' => 'Judul gambar harus diisikan',
                'min' => 'Judul minimal 4 karakter'
            ],
            'filefoto' => [
                'required' => 'Foto gambar harus diisikan',
                'image' => 'Jenis file harus gambar',
                'max' => 'Maksimal file 5MB'
            ]
        ]);
        unset($v['filefoto']);

        $v['product_id'] = $this->product_id;
        $v['description'] = $this->description;
        $v['is_primary'] = (int)$this->is_primary;
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
        $v = $this->validasi();
        $v['path_file'] = '';
        $v['created_at'] = Carbon::now();

        try {
            $m = ProductImageModel::query()->insert($v);
            $lastid = DB::getPdo()->lastInsertId();
            if($this->is_primary){
                ProductImageModel::query()->where('product_id', $this->product_id)->update(['is_primary'=>0]);
                ProductImageModel::query()->where('id', $lastid)->update(['is_primary'=>1]);
            }

            if(is_object($this->filefoto)){
                $path = $this->filefoto->storeAs('produk', $lastid . '.png');
                ProductImageModel::query()
                    ->where('id', $lastid)
                    ->update(['path_file'=>$path]);
            }
            session()->flash('success', 'Data berhasil di simpan');
            $this->edit(0);
            $this->dispatch('refresh');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $m = ProductImageModel::query()->where('id', $this->lm->id)->update($v);

            $lastid = $this->lm->id;
            if($this->is_primary){
                ProductImageModel::query()->where('product_id', $this->product_id)->update(['is_primary'=>0]);
                ProductImageModel::query()->where('id', $lastid)->update(['is_primary'=>1]);
            }

            if(is_object($this->filefoto)){
                $lastid = $this->lm->id;
                $path = $this->filefoto->storeAs('produk', $lastid . '.png');
                ProductImageModel::query()
                    ->where('id', $lastid)
                    ->update(['path_file'=>$path]);
            }

            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.produkimage.form');
    }
}
