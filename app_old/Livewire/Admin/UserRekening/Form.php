<?php

namespace App\Livewire\Admin\UserRekening;

use App\Models\BankModel;
use App\Models\UserModel;
use App\Models\UserRekeningModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public $user;
    public $id;
    public $banks;
    public $user_id;
    public $bank_id;
    public $an;
    public $no_rekening;
    public $bank_kota;
    public $is_primary;
    public $editmode = false;
    public $rekenings;
    public $rekening;

    public function mount(){
        $this->banks = BankModel::get();
    }

    public function edit($id){
        $this->rekening = UserRekeningModel::view()->find($id);
        $this->editmode = $this->rekening != null;
        $this->id = $this->rekening?->id;
        $this->user_id = $this->rekening?->user_id;
        $this->bank_id = $this->rekening?->bank_id;
        $this->an = $this->rekening?->an;
        $this->is_primary = $this->rekening?->is_primary ?? false;
        $this->no_rekening = $this->rekening?->no_rekening;
        $this->bank_kota = $this->rekening?->bank_kota;
    }

    public function editUser($iduser){
        $this->user = UserModel::find($iduser );
        $this->rekenings = UserRekeningModel::view()->where('user_id', $this->user?->id)->get();
    }

    public function setIsPrimary($value){
        $this->is_primary = $value == 'on' ? 1 : 0;
    }

    public function delete($id){
        UserRekeningModel::where('id', $id)->delete();
        $this->rekenings = UserRekeningModel::view()->where('user_id', $this->user?->id)->get();
    }


    private function validasi()
    {
        $v = $this->validate([
            'bank_id' => 'required', // Add validation for bank_id
            'an' => 'required', // Add validation for bank_id
            'no_rekening' => 'required', // Add validation for no_rekening
            'bank_kota' => 'required', // Add validation for no_rekening
        ], [
            'bank_id' => [
                'required' => 'Bank harus diisikan',
            ],
            'bank_kota' => [
                'required' => 'Kota Bank harus diisikan',
            ],

            'an' => [
                'required' => 'Atas nama rekening harus diisikan',
            ],
            'no_rekening' => [
                'required' => 'Nomor Rekening harus diisikan',
            ],
        ]);
        $v['user_id'] = $this->user->id;
        $v['is_primary'] = $this->is_primary;
        return $v;
    }

    public function save(){
        if($this->editmode){
            $this->update();
        }else{
            $this->store();
        }
    }

    public function store(){
        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $id = UserRekeningModel::query()->insertGetId($v);
            $this->bank_id = null;
            $this->an = '';
            $this->no_rekening = '';
            $this->bank_kota = '';
            $this->is_primary = false;

            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            $m = UserRekeningModel::query()->where('id', $this->rekening->id)->update($v);
            $this->bank_id = null;
            $this->an = '';
            $this->no_rekening = '';
            $this->bank_kota = '';
            $this->is_primary = false;
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        $this->rekenings = UserRekeningModel::view()->where('user_id', $this->user?->id)->get();
        return view('livewire.admin.user-rekening.form');
    }
}
