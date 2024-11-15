<?php

namespace App\Livewire\Admin\Bank;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\BankModel;
use App\Models\CabangModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    public $name;
    public $akronim;
    public $logo_path;
    public $kode_bank;
    public $urlgambar;
    public $file_base64;
    public $editform = false;
    public $lm;

    public function edit($id){
        if(!ValidatedPermission::authorize('Bank.Read')){
            return;
        }

        $this->lm = BankModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->name = $this->lm?->name ?? '';
        $this->kode_bank = $this->lm?->kode_bank ?? '';
        $this->akronim = $this->lm?->akronim ?? '';
        $this->logo_path = $this->lm?->logo_path ?? '-';
        if(\Storage::exists($this->logo_path)){
            $this->logo_path = url('admin/bank/'.$id.'.png');
        }else{
            $this->logo_path = '';
        }
    }

    private function validasi(){
        $v =   $this->validate([
            'name' => 'required|min:3',
            'akronim' => 'required|min:3',
            'kode_bank' => 'required|min:3',
        ],[
            'name.required' => 'Nama Bank harus diisikan',
            'name.min' => 'Nama Bank Minimal 3 karakter',
            'akronim.required' => 'Akronim nama Bank harus diisikan',
            'akronim.min' => 'Akronim nama bank Minimal 3 karakter',
            'kode_bank.required' => 'Kode bank harus diisikan'
        ]);

        return $v;
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
    }

    public function newForm(){
        $this->edit(0);
    }

    private function saveFileLogo($id){
        $dec = decodeBase64Image($this->file_base64);
        if( $dec !== false ){
            $path = 'logobank/'.$id.'.png';
            if(\Storage::put( $path, $dec)) {
                $arc = BankModel::find($id);
                $arc->logo_path = $path;
                $arc->save();
            }
        }
    }

    public function store(){
        if(!ValidatedPermission::authorize('Bank.Store')){
            return;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $id = BankModel::query()->insertGetId($v);
            $this->saveFileLogo($id);
            session()->flash('success', 'Data berhasil di simpan');
            $this->edit(0);
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_BANK, 'Menambahs data bank', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }


    public function update(){
        if(!ValidatedPermission::authorize('Bank.Update')){
            return;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            $m = BankModel::query()->where('id', $this->lm->id)->update($v);
            $this->saveFileLogo($this->lm->id);
            session()->flash('success', 'Data berhasil diubah');
            $this->edit($this->lm->id);
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_BANK, 'Merubah data bank', $v);

        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.bank.form');
    }
}
