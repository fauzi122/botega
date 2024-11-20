<?php

namespace App\Livewire\Admin\Redeempoint;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\MemberPointModel;
use App\Models\MemberRewardModel;
use App\Models\RequestUpdateModel;
use App\Models\RewardModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{

    public $user_id;
    public $point;
    public $reward_id;
    public $pengelola_user_id;
    public $approved_at;
    public $notes;
    public $editform = false;
    public $lm;
    public $member;
    public $reward;
    public $status;
    public $confirm = false;
    public $pesan = '';

    public function edit($id)
    {
        $this->lm = MemberRewardModel::view()->find($id);
        $this->editform = $this->lm != null;
        $this->member = $this->lm?->first_name . ' ' . $this->lm?->last_name . ' (' . $this->lm?->id_no . ')';
        $this->reward = $this->lm?->reward_code . ' ' . $this->lm?->reward . ' (' . $this->lm?->reward_point . ')';
        $this->user_id = $this->lm?->user_id ?? '';
        $this->point = $this->lm?->point ?? '';
        $this->reward_id = $this->lm?->reward_id ?? '';
        $this->pengelola_user_id = $this->lm?->pengelola_user_id ?? '';
        $this->approved_at = $this->lm?->approved_at ?? '';
        $this->status = $this->lm?->status ?? 0;

        $this->notes = $this->lm?->notes ?? '';
        if ($this->approved_at != '') {
            $this->approved_at = Carbon::parse($this->approved_at)->format('Y-m-d');
        }
    }

    public function newForm()
    {
        $this->edit(0);
    }

    private function validasi()
    {
        $v = $this->validate([
            'user_id' => 'required',
            'point' => 'required|numeric',
            'status' => 'required|numeric',
            'reward_id' => 'required',
            'notes' => 'nullable',
        ], [
            'user_id' => 'Member harus diisikan',
            'point' => 'Point harus berupa angka',
            'status' => 'Status Pengajuan Redeem Point',
            'reward_id' => 'Reward harus diisikan',
            'notes' => 'Notes boleh kosong',
        ]);
        foreach ($v as $k => $val) {
            if ($val == '') {
                unset($v[$k]);
            }
        }
        $v['pengelola_user_id'] = session('admin')->id;

        if ($this->status == 2) {
            $v['approved_at'] = Carbon::now();
        } else {
            $v['approved_at'] = null;
        }

        return $v;
    }

    public function showPoint($idreward)
    {
        $r = RewardModel::find($idreward);
        $this->point = $r?->point;
    }

    public function save()
    {
        if ($this->isPointCukup() || $this->confirm == true) {
            $this->confirm = false;
            if ($this->editform) {
                $this->update();
            } else {
                $this->store();
            }
        }
    }

    private function isPointCukup()
    {
        $this->pesan = '';
        if ($this->status != 2) return true;

        $user = UserModel::find($this->user_id);

        if ($user->point > $this->point) {
            return true;
        }

        $this->pesan = 'Point member tidak mencukupi. Point yang diusulkan ' . $this->point . ' namun member hanya memiliki ' . ($user->points ?? 0) . ' point.';
        return false;
    }

    private function accRedeemPoint($memberrewardid)
    {
        $data = [
            'points' => $this->point * -1,
            'notes' => 'Redeem point reward ',
            'pengelola_user_id' => session('admin')->id,
            'created_at' => Carbon::now()
        ];

        $cek = MemberPointModel::where([
            'user_id' => $this->user_id,
            'member_reward_id' => $memberrewardid
        ])->first();
        if ($cek == null) {
            $u = UserModel::find($this->user_id);
            $u->points = ($u->points ?? 0) - doubleval($this->point);
            $u->save();
        }

        MemberPointModel::updateOrInsert([
            'user_id' => $this->user_id,
            'member_reward_id' => $memberrewardid
        ], $data);

        LogController::writeLog(ValidatedPermission::UBAH_DATA_REDEEM_POINT, 'Redeem Point di setujui', $data, 0, $this->user_id);
    }

    private function retractRedeemPoint($memberrewardid)
    {
        $data = [
            'user_id' => $this->user_id,
            'member_reward_id' => $memberrewardid
        ];

        $cek = MemberPointModel::where($data)->first();
        if ($cek != null) {
            $u = UserModel::find($this->user_id);
            $u->points = ($u->points ?? 0) + doubleval($this->point);
            $u->save();
            MemberPointModel::where($data)->delete();
            LogController::writeLog(ValidatedPermission::UBAH_DATA_REDEEM_POINT, 'Pembatalan persetujuan Redeem Point', $data, 0, $this->user_id);
        }
    }
    public function store()
    {
        $v = $this->validasi();
        $v['created_at'] = Carbon::now();
        try {
            $lastid = MemberRewardModel::query()->insertGetId($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
            if ($this->status == 2) {
                $this->accRedeemPoint($lastid);
            } else {
                $this->retractRedeemPoint($lastid);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Data Gagal disimpan' . $e->getMessage());
        }
    }

    public function update()
    {
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $lastid = $this->lm->id;
            $m = MemberRewardModel::query()->where('id', $lastid)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
            if ($this->status == 2) {
                $this->accRedeemPoint($lastid);
            } else {
                $this->retractRedeemPoint($lastid);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Data Gagal diubah' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.redeempoint.form');
    }
}
