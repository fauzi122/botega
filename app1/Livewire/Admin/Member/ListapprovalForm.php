<?php

namespace App\Livewire\Admin\Member;

use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use Livewire\Component;

class ListapprovalForm extends Component
{
    protected $listeners = ['edit', 'newForm'];

    public $level_name;
    public $level;
    public $publish;
    public $editform = false;
    public $lm;

    public function edit($id){
        $this->lm = RequestUpdateModel::query()->find($id);
        $this->editform= $this->lm != null;
        $this->level_name = $this->lm?->level_name ?? '';
        $this->level = $this->lm?->level;
        $this->publish = $this->lm?->publish ?? 1;
    }

    public function newForm(){
        $this->edit(0);
    }

    private function validasi(){
        return   $this->validate([
            'level_name' => 'required|min:4',
            'level' => 'required',
            'publish' => 'required'
        ],[
            'level_name' => [
                'required' => 'Nama Level harus diisikan',
                'min' => 'Nama level minimal 4 karakter'
            ],
            'level' => 'Level harus diisikan',
            'publish' => 'Publish harus diisikan'
        ]);
    }

    public function store(){
        $v = $this->validasi();

        try {
            $m = LevelMemberModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        $v = $this->validasi();

        try {
            $m = LevelMemberModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.member.listapproval-form');
    }
}
