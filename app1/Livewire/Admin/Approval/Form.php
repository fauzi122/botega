<?php

namespace App\Livewire\Admin\Approval;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\CabangModel;
use App\Models\LevelMemberModel;
use App\Models\RequestUpdateModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $hp;
    public $gender;
    public $tanggal_lahir;
    public $alamat;
    public $rt;
    public $rw;
    public $npwp;
    public $nppk;
    public $nik;
    public $editform = false;
    public $lm;
    public $reason_user;
    public $birth_at;
    public $home_addr;
    public $zip_code;
    public $phone;
    public $wa;
    public $web;
    public $fax;
    public $nppkp;

    public $n_first_name;
    public $n_last_name;
    public $n_email;
    public $n_hp;
    public $n_gender;
    public $n_tanggal_lahir;
    public $n_alamat;
    public $n_rt;
    public $n_rw;
    public $n_npwp;
    public $n_nik;
    public $n_birth_at;
    public $n_home_addr;
    public $n_zip_code;
    public $n_phone;
    public $n_wa;
    public $n_fax;
    public $n_web;
    public $n_nppkp;
    public $idrequest;
    public $status;
    public $reason_admin;


    public function edit($id)
    {
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)){
            return;
        }

        $this->idrequest = $id;
        $ru = RequestUpdateModel::find($id);
        $this->reason_user = $ru->reason_user;
        $this->reason_admin = $ru->reason_admin;
        $this->lm = UserModel::find($ru->user_id);
        $this->editform = $this->lm != null;
        $this->first_name = $this->lm?->first_name ?? '';
        $this->last_name = $this->lm?->last_name ?? '';
        $this->email = $this->lm?->email ?? '';
        $this->hp = $this->lm?->hp ?? '';
        $this->gender = $this->lm?->gender ?? '';
        $this->tanggal_lahir = $this->lm?->tanggal_lahir ?? null;
        $this->alamat = $this->lm?->alamat ?? '';
        $this->rt = $this->lm?->rt ?? '';
        $this->rw = $this->lm?->rw ?? '';
        $this->npwp = $this->lm?->npwp ?? '';
        $this->nppk = $this->lm?->nppk ?? '';
        $this->nik = $this->lm?->nik ?? '';
        $this->status = $this->lm?->status ?? 'Approved';

        $json = json_decode($ru?->json_temp, true);
        if($json != null){
            $this->n_first_name = $json['first_name'] ?? '';
            $this->n_last_name = $json['last_name'] ?? '';
            $this->n_email = $json['email'] ?? '';
            $this->n_hp = $json['hp'] ?? '';
            $this->n_gender = $json['gender'] ?? '';
            $this->n_tanggal_lahir = $json['tanggal_lahir'] ?? '';
            $this->n_alamat = $json['alamat'] ?? '';
            $this->n_rt = $json['rt'] ?? '';
            $this->n_rw = $json['rw'] ?? '';
            $this->n_nppk = $json['nppk'] ?? '';
            $this->n_npwp = $json['npwp'] ?? '';
            $this->n_nik = $json['nik'] ?? '';
        }
    }

    private function validasi()
    {
        $r = $this->validate([
            'n_first_name' => 'required|string|max:255',
            'n_last_name' => 'required|string|max:255',
            'n_email' => 'required|email|max:255',
            'n_hp' => 'nullable|string|max:255',
            'n_gender' => 'required|string|max:255',
            'n_birth_at' => 'nullable|date',
            'n_home_addr' => 'nullable|string|max:255',
            'n_rt' => 'nullable|string|max:255',
            'n_rw' => 'nullable|string|max:255',
            'n_npwp' => 'nullable|string|max:255',
            'n_nppkp' => 'nullable|string|max:255',
            'n_nik' => 'nullable|string|max:255',
            'n_zip_code' => 'nullable|string|max:255',
            'n_phone' => 'nullable|string|max:255',
            'n_wa' => 'nullable|string|max:255',
            'n_fax' => 'nullable|string|max:255',
            'n_web' => 'nullable|string|max:255',
            'n_nppkp' => 'nullable|string|max:255',
        ]);
        $tmp = [];
        foreach($r as $k=>$v){
            $field = substr($k, 2);
            $tmp[$field] = $v;
        }
        $tmp['updated_at'] = Carbon::now();
        return $tmp;
    }

    public function newForm(){
        $this->edit(0);
    }

    public function save(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_MEMBER)){
            return ;
        }

        try {
            if($this->status == 'Approved') {
                $v = $this->validasi();
                $m = UserModel::query()->where('id', $this->lm->id)->update($v);
            }

            RequestUpdateModel::where('id',$this->idrequest)->update([
               'pengelola_user_id' => session('admin')?->id,
               'status' => $this->status,
               'reason_admin' => $this->reason_admin,
            ]);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');

            LogController::writeLog(ValidatedPermission::UBAH_DATA_MEMBER, 'Menyimpan pengajuan permintaan data', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.approval.form');
    }
}
