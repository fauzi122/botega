<?php

namespace App\Livewire\Admin\Event;

use App\Models\EventGaleryModel;
use App\Models\EventsModel;
use Carbon\Carbon;
use Livewire\Component;

class Detailgaleries extends Component
{
    public $title;
    public $description;
    public $path_file;
    public $event_id;
    public $listgaleries;
    public $event;
    public $galeri;
    public $urlfoto = '';

    public function newData($event_id){
        $this->event_id = $event_id;
        $this->galeri = null;
        $this->event = EventsModel::find($event_id);
        $this->title = '';
        $this->description = '';
        $this->path_file = '';
        $this->urlfoto = '';
    }

    public function edit($id){
        $this->galeri = EventGaleryModel::query()->find($id);
        $this->title = $this->galeri?->title ?? '';
        $this->description = $this->galeri?->description ?? '';
        $this->path_file = $this->galeri?->path_file ?? '--';
        $this->urlfoto = '';
        if(\Storage::exists($this->path_file)){
            $this->urlfoto = url('/admin/event/images/'.$this->galeri?->id.'.png');
        }
    }

    private function validasi(){
        $v = $this->validate([
            'event_id' => 'required',
            'title' => 'required|min:5',
            'path_file' => function($attr, $value, $fail){
                if($value == '' && $this->galeri == null){
                    return $fail('Gambar tidak boleh kosong');
                }
                $f = decodeBase64Image($value);
                if($f == false && $this->galeri == null){
                    $fail('File bukan gambar ');
                }
            }

        ],[
            'event_id.required' => 'Event tidak valid',
            'title.required'    => 'Judul galeri harus diisikan'
        ]);
        $v['description'] = $this->description;
        $v['user_id'] = session('admin')->id;
        unset($v['path_file']);
        return $v;
    }

    public function save(){
       $v = $this->validasi();

       try{
           if($this->galeri == null){
               $v['created_at'] = Carbon::now();
               $id = EventGaleryModel::query()->insertGetId($v);
           }else{
               $id = $this->galeri?->id;
               $v['updated_at'] = Carbon::now();
               EventGaleryModel::query()->where('id', $id)->update($v);
           }

           $dec = decodeBase64Image($this->path_file);
           if($dec != false){
               $path = 'galeri-event/'.$id.'.png';
               \Storage::put($path, $dec);
               $egm = EventGaleryModel::query()->find($id);
               $egm->path_file = $path;
               $egm->save();
           }
           $this->newData($this->event_id);
           $this->dispatch('refresh');
       }catch (\Exception $e){
           session()->flash('error', 'Gagal simpan data galery');
       }
    }

    public function delete($id){
        $f = EventGaleryModel::query()->where('id', $id)->first();
        if($f == null) return;
        if(\Storage::exists($f->path_file ?? '--')){
            \Storage::delete($f->path_file);
        }
        $f->delete();
    }

    public function render()
    {
        $this->listgaleries = EventGaleryModel::query()->where('event_id', $this->event_id)->get();
        return view('livewire.admin.event.detailgaleries');
    }
}
