<?php

namespace App\Livewire\Admin\Katalogproduk;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\KatalogProdukModel;
use App\Models\LevelMemberModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Form extends Component
{

    public $nama_katalog;
    public $file_katalog;
    public $gambar_katalog;
    public $filefoto;
    public $fileunduh;
    public $urlfoto;
    public $urlunduh;
    public $editform = false;
    public $lm;
    public $success =false;
    public $lvlmember;
    public $lvl_member_id;

    public function edit($id){

        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_KATALOG)){
            return ;
        }

        $this->lm = KatalogProdukModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->nama_katalog = $this->lm?->nama_katalog ?? '';
        $this->lvl_member_id = $this->lm?->lvl_member_id;

        $this->urlfoto = '';
        $this->urlunduh = '';
        if(Storage::exists($this->lm?->gambar_katalog ?? '--') && $this->lm?->gambar_katalog != ''){
            $this->urlfoto = url('admin/katalog-produk/image/'.$this->lm?->id.'.png');
        }
        if(Storage::exists($this->lm?->file_katalog ?? '--') &&$this->lm?->file_katalog != ''){
            $this->urlunduh = url('admin/katalog-produk/berkas/'.$this->lm?->id.'.pdf');
        }
    }

    public function newForm(){
        $this->edit(0);
    }

    public function delete($id){

        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_KATALOG)){
            return ;
        }

        $p = KatalogProdukModel::query()->where('id',$id)->first();
        if(Storage::exists($p->gambar_katalog)){
            Storage::delete($p->gambar_katalog);
        }
        if(Storage::exists($p->file_katalog)){
            Storage::delete($p->file_katalog);
        }
        KatalogProdukModel::query()->where('id',$id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_KATALOG, 'Hapus katalog produk ', $id);
    }

    public function hapusgambar(){
        $arc = KatalogProdukModel::find($this->lm?->id);
        if($arc == null)return false;
        if(\Storage::exists($arc->gambar_katalog)) {
            \Storage::delete($arc->gambar_katalog);
        }
        $arc->gambar_katalog = '';
        $arc->save();
        LogController::writeLog(ValidatedPermission::UBAH_DATA_KATALOG, 'Hapus gambar katalog produk ',[
            'id' => $this->lm?->id,
            'path' => $arc->gambar_katalog
        ]);
    }

    public function hapusFileKatalog(){

        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_KATALOG)){
            return ;
        }

        $arc = KatalogProdukModel::find($this->lm?->id);
        if($arc == null)return false;
        if(\Storage::exists($arc->file_katalog)) {
            \Storage::delete($arc->file_katalog);
        }
        $arc->file_katalog = '';
        $arc->save();
        LogController::writeLog(ValidatedPermission::UBAH_DATA_KATALOG, 'Hapus file pdf katalog produk ',[
            'id' => $this->lm?->id,
            'path' => $arc->file_katalog
        ]);
    }


    private function validasi(){
        $v =   $this->validate([
            'nama_katalog' => 'required|min:4',
            'lvl_member_id' => 'required|exists:level_member,id',
            'filefoto' => 'required',
            'fileunduh' => 'required',
        ],[
            'nama_katalog.required' => 'Judul katalog harus diisikan',
            'nama_katalog.min' => 'Judul minimal 4 karakter',
            'lvl_member_id' => 'Pilih Level member',
            'filefoto' => 'Gambar katalog harus diisikan',
            'fileunduh' => 'File katalog harus diisikan'

        ]);
        unset($v['filefoto']);
        unset($v['fileunduh']);
        $v['user_id'] = session('admin')?->id;

        return $v;
    }

    public function save(){
        $this->success = false;
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    public function store(){

        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_KATALOG)){
            return ;
        }
        $v = $this->validasi();
        $v['file_katalog'] = '';
        $v['gambar_katalog'] = '';
        $v['created_at'] = Carbon::now();

        try {
            $lastid = KatalogProdukModel::query()->insertGetId($v);

            $arc = KatalogProdukModel::find($lastid);

            $dec = decodeBase64Image($this->filefoto);
            if( $dec !== false ){
                $path = 'katalog-produk/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc->gambar_katalog = $path;
                }
            }

            $dec = decodeBase64File($this->fileunduh, 'data:application');
            if( $dec !== false ){
                $path = 'katalog-unduh/'.$lastid.'.pdf';
                if(\Storage::put( $path, $dec)) {
                    $arc->file_katalog = $path;
                }
            }

            $arc->save();

            session()->flash('success', 'Data berhasil di simpan');
            $this->edit(0);
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_KATALOG, 'Menambah katalog produk ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){

        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_KATALOG)){
            return ;
        }
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $m = KatalogProdukModel::query()->where('id', $this->lm->id)->update($v);
            $lastid = $this->lm->id;

            $arc = KatalogProdukModel::find($lastid);

            $dec = decodeBase64Image($this->filefoto);
            if( $dec !== false ){
                $path = 'katalog-produk/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc->gambar_katalog = $path;
                }
            }

            $dec = decodeBase64File($this->fileunduh, 'data:application');
            if( $dec !== false ){
                $path = 'katalog-unduh/'.$lastid.'.pdf';
                if(\Storage::put( $path, $dec)) {
                    $arc->file_katalog = $path;
                }
            }

            $arc->save();

            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');

            LogController::writeLog(ValidatedPermission::UBAH_DATA_KATALOG, 'Merubah katalog produk ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        $this->lvlmember = LevelMemberModel::query()->orderBy('level', 'asc')->get();
        return view('livewire.admin.katalogproduk.form');
    }
}
