<?php

namespace App\Livewire\Admin\Gift;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\GiftModel;
use App\Models\GiftTypeModel;
use App\Models\ProductCategoryModel;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{

    public $user_id;
    public $gift_type_id;
    public $pengelola_user_id;
    public $sent_at;
    public $price;
    public $notes;
    public $received_at;
    public $editform = false;
    public $lm;
    public $giftTypes;
    public $member;

    public function mount(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_JENIS_HADIAH)){
            return ;
        }

        // $this->giftTypes = GiftTypeModel::get();
    }

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_HADIAH)){
            return ;
        }

        $this->lm = GiftModel::view()->find($id);
        $this->editform= $this->lm != null;
        $this->user_id = $this->lm?->user_id;
        $this->member = $this->lm?->first_name . ' ' . $this->lm?->last_name . " ({$this->lm?->id_no})";
        $this->gift_type_id = $this->lm?->gift_type_id;
        $this->pengelola_user_id = $this->lm?->pengelola_user_id;
        $this->sent_at = $this->lm?->sent_at;
        $this->price = $this->lm?->price;
        $this->notes = $this->lm?->notes;
        $this->received_at = $this->lm?->received_at;
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        $v =   $this->validate([
            'user_id' => 'required|exists:users,id',
            'gift_type_id' => 'required|exists:gift_types,id',
        ],[
            'user_id'=>'Member harus dipilih',
            'gift_type'=>'Jenis Hadiah harus dipilih',
        ]);
        $v['pengelola_user_id'] = session('admin')?->id;
        $v['sent_at'] = $this->sent_at;
        $v['received_at'] = $this?->received_at ?? '';
        if($v['received_at'] == ''){
            $v['received_at'] = null;
        }
        $v['price'] = doubleval($this->price);
        $v['notes'] = $this->notes;

        return $v;
    }

    public function dapatHarga(){
        $m = GiftTypeModel::find($this->gift_type_id);
        $this->price = $m->price;
        $this->dispatch('dapatharga');
    }

    public function giftType(){
        $user = User::find($this->user_id);
        $this->giftTypes = GiftTypeModel::where('level_member_id', $user->level_member_id)->get();
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_HADIAH)){
            return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $m = GiftModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
           $this->dispatch('refresh');

            if($this->sent_at != null){
                $tgl = Carbon::parse($this->sent_at)->translatedFormat('l, d F Y');
                $gift = GiftTypeModel::query()->where('id', $this->gift_type_id)->first();
                LogController::writeLog('Pengiriman Gift', 'Gift '.($gift?->name).' telah dikirimkan pada '.$tgl, $v, 0,
                    $this->user_id
                );
            }

            if($this->received_at != null ){
                $tgl = Carbon::parse($this->received_at)->translatedFormat('l, d F Y');
                $gift = GiftTypeModel::query()->where('id', $this->gift_type_id)->first();
                LogController::writeLog('Gift Telah diterima', 'Gift '.($gift?->name).' telah diterima member pada '.$tgl, $v, 0,
                    $this->user_id
                );
            }

            $this->edit(0);

            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_HADIAH, 'Tambah data hadiah', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_HADIAH)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {

            $m = GiftModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');

            if($this->sent_at != null && $this->lm?->sent_at == null){
                $tgl = Carbon::parse($this->sent_at)->translatedFormat('l, d F Y');
                $gift = GiftTypeModel::query()->where('id', $this->gift_type_id)->first();
                LogController::writeLog('Pengiriman Gift', 'Gift '.($gift?->name).' telah dikirimkan pada '.$tgl, $v, 0,
                    $this->user_id
                );
            }

            if($this->received_at != null && $this->lm?->received_at == null){
                $tgl = Carbon::parse($this->received_at)->translatedFormat('l, d F Y');
                $gift = GiftTypeModel::query()->where('id', $this->gift_type_id)->first();
                LogController::writeLog('Gift Telah diterima', 'Gift '.($gift?->name).' telah diterima member pada '.$tgl, $v, 0,
                    $this->user_id
                );
            }
            LogController::writeLog(ValidatedPermission::UBAH_DATA_HADIAH, 'Ubah data  hadiah', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.gift.form');
    }
}
