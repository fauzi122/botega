<?php

namespace App\Livewire\Admin\Slider;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\ArticleModel;
use App\Models\ProductImageModel;
use App\Models\SliderModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{

    public $title;
    public $description;
    public $image_path;
    public $order;
    public $filefoto;
    public $editform = false;
    public $lm;
    public $urlfilefoto;

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_SLIDER)){
            return ;
        }

        $this->lm = SliderModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->title = $this->lm?->title ?? '';
        $this->description =  $this->lm?->description ?? '';
        $this->order = $this->lm?->order ?? 0;
        $this->urlfilefoto = '';
        if(Storage::exists($this->lm?->image_path ?? '--') && $this->image_path != ''){
            $this->urlfilefoto = url('admin/slider/image/'.$this->lm?->id.'.png');
        }
    }

    public function newForm(){
        $this->edit(0);
    }

    public function delete($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_SLIDER)){
            return ;
        }


        $p = SliderModel::query()->where('id',$id)->first();
        if(Storage::exists($p->image_path)){
            Storage::delete($p->image_path);
        }
        SliderModel::query()->where('id',$id)->delete();
    }

    public function hapusgambar(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_SLIDER)){
            return ;
        }


        $arc = SliderModel::find($this->lm?->id);
        if($arc == null)return false;
        if(\Storage::exists($arc->image_path)) {
            \Storage::delete($arc->image_path);
        }
        $arc->image_path = '';
        $arc->save();
        LogController::writeLog(ValidatedPermission::UBAH_DATA_SLIDER, 'mENGHAPUS GAMBAR SLIDER', [
            'ID' => $this->lm?->id,
            'path' => $arc->image_path
        ]);
    }


    private function validasi(){
        $v =   $this->validate([
            'title' => 'required|min:4',
        ],[
            'title' => [
                'required' => 'Judul gambar harus diisikan',
                'min' => 'Judul minimal 4 karakter'
            ],
            'filefoto' => [
                'required' => 'Foto gambar harus diisikan',
                'image' => 'Jenis file harus gambar',
                'max' => 'Maksimal file 5MB'
            ]
        ]);

        $v['order'] = $this->order;
        $v['description'] = $this->description;
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
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_SLIDER)){
            return ;
        }

        $v = $this->validasi();
        $v['image_path'] = '';
        $v['created_at'] = Carbon::now();

        try {
            $lastid = SliderModel::query()->insertGetId($v);

            $dec = decodeBase64Image($this->filefoto);
            if( $dec !== false ){
                $path = 'slider/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = SliderModel::find($lastid);
                    $arc->image_path = $path;
                    $arc->save();
                }
            }

            session()->flash('success', 'Data berhasil di simpan');
            $this->edit(0);
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_SLIDER, 'Menambah slider', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_SLIDER)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $m = SliderModel::query()->where('id', $this->lm->id)->update($v);
            $lastid = $this->lm->id;

            $dec = decodeBase64Image($this->filefoto);
            if( $dec !== false ){
                $path = 'slider/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = SliderModel::find($lastid);
                    $arc->image_path = $path;
                    $arc->save();
                }
            }

            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_SLIDER, 'Merubah data slider', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.slider.form');
    }
}
