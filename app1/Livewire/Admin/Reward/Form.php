<?php

namespace App\Livewire\Admin\Reward;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use App\Models\RewardModel;
use App\Models\SliderModel;
use Livewire\Component;
use PHPUnit\Exception;

class Form extends Component
{

    protected $listeners = ['edit', 'newForm'];

    public $code;
    public $name;
    public $point;
    public $expired_at;
    public $descriptions;
    public $editform = false;
    public $path_image;
    public $lm;
    public $imgsrc;
    public $file_image;

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_REWARD)){
            return ;
        }

        $this->lm = RewardModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->code = $this->lm?->code ?? '';
        $this->name = $this->lm?->name ?? '';
        $this->point = $this->lm?->point ?? '';
        $this->expired_at = $this->lm?->expired_at ?? '';
        $this->descriptions = $this->lm?->descriptions ?? '';
        $this->path_image = $this->lm?->path_image ?? '-';
        $this->imgsrc = null;
        if(\Storage::exists($this->path_image)){
            $this->imgsrc = url('admin/reward/pic/'.$this->lm?->id.'.png');
        }

    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        $v = $this->validate([
            'code' => 'required|unique:rewards,code,'.$this->lm?->id,
            'name' => 'required',
            'point' => 'numeric',
            'expired_at' => 'required|date',
            'file_image' => function($attr, $value, $fail){
                if($value == '' && $this->imgsrc == null){
                    return $fail('Gambar tidak boleh kosong');
                }
                $f = decodeBase64Image($value);
                if($f == false && $this->imgsrc == null){
                    $fail('File bukan gambar ');
                }
            }

        ], [
            'code.required' => 'Kode harus diisikan',
            'code.unique' => 'Kode sudah ada, gunakan kode lain',
            'name.required' => 'Name harus diisikan',
            'point.numeric' => 'Point harus berupa angka',
            'expired_at.required' => 'Tanggal kadalurasa harus diisikan',
            'expired_at.date' => 'Expired At harus berupa format tanggal yang valid',
        ]);
        $v['descriptions'] = $this->descriptions;
        unset($v['file_image']);

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
            $lastid = RewardModel::query()->insertGetId($v);

            $dec = decodeBase64Image($this->file_image);
            if( $dec !== false ){
                $path = 'reward/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = RewardModel::find($lastid);
                    $arc->path_image = $path;
                    $arc->save();
                }
            }

            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_REWARD, 'Tambah data Reward', $v);
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
            $m = RewardModel::query()->where('id', $lastid)->update($v);

            $dec = decodeBase64Image($this->file_image);
            if( $dec !== false ){
                $path = 'reward/'.$lastid.'.png';
                if(\Storage::put( $path, $dec)) {
                    $arc = RewardModel::find($lastid);
                    $arc->path_image = $path;
                    $arc->save();
                }
            }

            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_REWARD, 'Ubah data Reward', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function removeImage(){
        try{
            $this->lm->path_image = null;
            $this->lm?->save();
        }catch (Exception $e){}
    }

    public function render()
    {
        return view('livewire.admin.reward.form');
    }
}
