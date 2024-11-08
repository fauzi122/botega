<?php

namespace App\Livewire\Admin\Roleaccessright;

use App\Models\AccessRightModel;
use App\Models\RequestUpdateModel;
use App\Models\RoleAccessRightModel;
use App\Models\RoleModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    public $ref_role_id;
    public $role_id;
    public $role;
    public $access_right_id;
    public $grant;
    public $editform = false;
    public $lm;
    public $access;
    public $module;

    public function mount($ref_role_id){
        $this->ref_role_id = $ref_role_id;
    }
    public function edit($id){
        $this->lm = RoleAccessRightModel::query()->find($id);
        $this->editform = $this->lm != null;
        $this->role_id = $this->lm?->role_id ;
        $this->role = RoleModel::query()->find($this->role_id);
        $this->access_right_id = $this->lm?->access_right_id;
        $this->grant = $this->lm?->grant;
        $access = AccessRightModel::query()->find($this->access_right_id);
        $this->access = $access?->name;
        $this->module = $access?->module;
    }


    private function validasi(){
        return $this->validate([
            'role_id' => 'required|exists:roles,id',
            'access_right_id' => 'required|exists:access_rights,id',
            'grant' => 'required',
        ],[
            'role_id' => 'Nama Peran Akses harus diisikan',
            'access_right_id' => 'Hak akses harus diisikan',
            'grant' => 'Mode akses harus diisikan',
        ]);
    }

    public function toggleGrant($id){
        $rarm = RoleAccessRightModel::query()->find($id);
        if($rarm == null)return;
        $rarm->grant = $rarm->grant == 1 ? 0 : 1;
        $rarm->save();
    }

    public function newForm(){
        $this->edit(0);
        $this->role = RoleModel::find($this->ref_role_id );
        $this->role_id = $this->ref_role_id;
    }

    public function store(){
        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $id = RoleAccessRightModel::query()->insertGetId($v);
            $this->edit($id);
            session()->flash('success', 'Data berhasil di simpan ' . $id);
            $this->dispatch('refreshData');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function save(){
        $r = RoleAccessRightModel::where([
            'role_id' => $this->role_id,
            'access_right_id' => $this->access_right_id
        ])->first();

        if($r == null){
            $this->store();
        }else{
            $this->lm = $r;
            $this->update();
        }

    }

    public function update(){
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            RoleAccessRightModel::query()->where('id', $this->lm->id)->update($v);
            $this->edit($this->lm->id);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }

    }

    public function render()
    {

        return view('livewire.admin.roleaccessright.form');
    }
}
