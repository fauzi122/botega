<?php

namespace App\Livewire\Admin\Levelmember;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\CabangModel;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use Livewire\Component;

class Form extends Component
{

    protected $listeners = ['edit', 'newForm'];

    public $level_name;
    public $level;
    public $kategori;
    public $description;
    public $limit_transaction;
    public $publish;
    public $editform = false;
    public $lm;

    protected $rulesFromOutside = [
        'limit_transaction' => 'numeric'
    ];

    public function edit($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_LEVEL_MEMBER)){
            return;
        }

        $this->lm = LevelMemberModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->level_name = $this->lm?->level_name ?? '';
        $this->level = $this->lm?->level ?? '';
        $this->kategori = $this->lm?->kategori ?? '';
        $this->description = $this->lm?->description ?? '';
        $this->limit_transaction = $this->lm?->limit_transaction ?? '';
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        $v =   $this->validate([
            'level_name' => 'required|min:3',
            'level' => 'required',
            'kategori' => 'required|in:MEMBER PRO,UMUM',
        ],[
            'level_name' => [
                'required' => 'Nama Level harus diisikan',
                'level' => 'Level harus ditentukan'
            ],
            'level' => 'Level harus ditentukan',
            'kategori' => 'Kategori harus ditentukan'
        ]);
        $v['publish'] = $this->publish;
        $v['description'] = $this->description;
        $v['limit_transaction'] = doubleval($this->limit_transaction);

        return $v;
    }

    public function store(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_LEVEL_MEMBER)){
            return;
        }

        $v = $this->validasi();

        try {
            $m = LevelMemberModel::query()->insert($v);
            $this->nama = '';
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');

            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_LEVEL_MEMBER, 'Menambah data level member', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function toggleAktif($id){

        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_LEVEL_MEMBER)){
            return;
        }

        LevelMemberModel::where('id', $id)->update(['publish'=>\DB::raw('CASE WHEN publish=1 THEN 0 ELSE 1 END')]);
        LogController::writeLog(ValidatedPermission::UBAH_DATA_LEVEL_MEMBER, 'Ubah data level member', $id);

    }

    public function update(){

        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_LEVEL_MEMBER)){
            return;
        }

        $v = $this->validasi();

        try {
            $m = LevelMemberModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_LEVEL_MEMBER, 'Merubah data level member', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.levelmember.form');
    }
}
