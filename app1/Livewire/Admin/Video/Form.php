<?php

namespace App\Livewire\Admin\Video;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\EventsModel;
use App\Models\YoutubeModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    public $title;
    public $iframe_code;
    public $link_youtube;
    public $editform = false;
    public $lm;

    public function edit($id){

        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_VIDEO_YOUTUBE)){
            return ;
        }
        $this->lm = YoutubeModel::query()->find($id);
        $this->editform = $this->lm != null;
        $this->title = $this->lm?->title ?? null;
        $this->iframe_code = $this->lm?->iframe_code ?? null;
        $this->link_youtube = $this->lm?->link_youtube ?? null;
    }

    private function validasi(){
        $v =  $this->validate([
            'title' => 'required|min:3',
            'link_youtube' => 'required|min:10',
        ], [
            'title' => 'Judul video harus diisikan',
            'link_youtube.required' => 'Link youtube harus diisikan',
            'link_yotube.min' => 'Link youtube harus http://youtube.com/',

        ]);
        return $v;
    }

    public function newForm(){
        $this->edit(0);
    }

    public function save(){

        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    public function store(){

        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_VIDEO_YOUTUBE)){
            return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();
        try {
            $m = YoutubeModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refresh');
            $this->edit(0);
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_VIDEO_YOUTUBE, 'Menambah video youtube ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){

        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_VIDEO_YOUTUBE)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $m = YoutubeModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
            $this->edit(0);
            LogController::writeLog(ValidatedPermission::UBAH_DATA_VIDEO_YOUTUBE, 'Merubah video youtube ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.video.form');
    }
}
