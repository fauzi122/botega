<?php

namespace App\Livewire\Admin\Redeempoint;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\MemberPointModel;
use App\Models\MemberRewardModel;
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
    public $remaining_points = '-'; // Sisa poin member
    public $required_points = '-'; // Poin yang dibutuhkan reward

    public function getMemberPoints($userId)
    {
        $user = UserModel::find($userId);
        $this->emit('updateMemberPoints', $user->points ?? 0);
    }

    public function getRewardPoints($rewardId)
    {
        $reward = RewardModel::find($rewardId);
        $this->emit('updateRewardPoints', $reward->points ?? 0);
    }

    public function edit($id)
    {
        $this->lm = MemberRewardModel::view()->find($id);
        $this->editform = true;

        if ($this->lm) {
            $this->member = $this->lm->first_name . ' ' . $this->lm->last_name . ' (' . $this->lm->id_no . ')';
            $this->reward = $this->lm->reward_code . ' ' . $this->lm->reward . ' (' . $this->lm->reward_point . ')';
            $this->user_id = $this->lm->user_id;
            $this->reward_id = $this->lm->reward_id;

            // Ambil poin dari tabel member_reward
            $this->point = $this->lm->points;

            // Tampilkan poin reward tanpa menghitung ulang
            $this->required_points = $this->lm->reward_point ?? '-';
            $this->remaining_points = '-'; // Biarkan kosong untuk mode edit
        }

        $this->notes = $this->lm->notes ?? '';
        $this->status = $this->lm->status ?? 0;

        if ($this->lm->approved_at != null) {
            $this->approved_at = Carbon::parse($this->lm->approved_at)->format('Y-m-d');
        }
    }




    public function newForm()
    {
        $this->editform = false; // Set mode tambah
        $this->reset(['user_id', 'reward_id', 'notes', 'remaining_points', 'required_points']);
    }
    public function resetState()
    {
        $this->reset(['remaining_points', 'required_points', 'point', 'reward_id']);
    }


    private function validasi()
    {
        $v = $this->validate([
            'user_id' => 'required',
            'point' => 'required|numeric',
            // 'status' => 'required|numeric',
            'reward_id' => 'required',
            'notes' => 'nullable',
        ], [
            'user_id' => 'Member harus diisikan',
            'point' => 'Point harus berupa angka',
            // 'status' => 'Status Pengajuan Redeem Point',
            'reward_id' => 'Reward harus diisikan',
            'notes' => 'Notes boleh kosong',
        ]);
        $v['pengelola_user_id'] = session('admin')->id;

        if ($this->status == 2) {
            $v['approved_at'] = Carbon::now();
        } else {
            $v['approved_at'] = null;
        }

        return $v;
    }
    public function updatedUserId()
    {
        if ($this->editform) {
            return; // Jika sedang dalam mode edit, abaikan perubahan
        }

        $user = UserModel::find($this->user_id);
        $this->point = $user?->points ?? '-';
        $this->remaining_points = '-'; // Reset nilai sisa poin
    }
    public function showPoint($rewardId)
    {
        if ($this->editform && $rewardId == $this->lm->reward_id) {
            // Jika sedang dalam mode edit dan reward tidak berubah, abaikan penghitungan ulang
            return;
        }

        $reward = RewardModel::find($rewardId); // Ambil data reward berdasarkan ID
        $this->required_points = $reward->point ?? 0; // Perbarui poin yang dibutuhkan
        $this->remaining_points = ($this->point ?? 0) - $this->required_points; // Hitung ulang sisa poin
    }




    public function save()
    {
        $isEdit = $this->editform; // Menentukan apakah sedang dalam mode edit
        $statusSebelumnya = $this->lm?->status ?? null;

        // Validasi jika dalam mode edit
        if ($isEdit) {
            // Cek apakah user_id berubah
            if ($this->user_id != $this->lm->user_id) {
                session()->flash('error', 'Member tidak boleh diubah.');
                return;
            }
        }

        try {
            // Validasi poin mencukupi
            if (!$this->isPointCukup() && !$this->confirm) {
                session()->flash('error', $this->pesan);
                return;
            }

            // Jika status berubah menjadi "Ditolak", kembalikan poin
            if ($isEdit && in_array($statusSebelumnya, [0, 1, 2]) && $this->status == 3) {
                $this->retractRedeemPoint($this->lm->id);
            }

            // Jika status berubah menjadi "Disetujui" dari pengajuan baru
            if (!$isEdit && $this->status == 2) {
                $this->accRedeemPoint(null);
            }

            // Jika sedang dalam mode edit
            if ($isEdit) {
                // Jika reward diubah, lakukan penyesuaian poin
                if ($this->reward_id != $this->lm->reward_id) {
                    $this->adjustPointsForRewardChange($this->lm->reward_id, $this->reward_id);
                }

                $this->update();
            } else {
                $this->store();
            }

            session()->flash('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    private function adjustPointsForRewardChange($oldRewardId, $newRewardId)
    {
        $user = UserModel::find($this->user_id);

        // Ambil reward lama dan baru
        $oldReward = RewardModel::find($oldRewardId);
        $newReward = RewardModel::find($newRewardId);

        // Jika salah satu reward tidak ditemukan, return
        if (!$oldReward || !$newReward) {
            throw new \Exception('Data reward tidak valid.');
        }

        // Hitung penyesuaian poin
        $adjustment = $oldReward->point - $newReward->point;
        $user->points += $adjustment; // Tambahkan/dikurangi dari poin user
        $user->save();
    }



    private function isPointCukup()
    {
        $user = UserModel::find($this->user_id);
        $reward = RewardModel::find($this->reward_id);

        if (!$user || !$reward) {
            $this->pesan = 'Data user atau reward tidak valid.';
            return false;
        }

        if (($user->points ?? 0) < ($reward->point ?? 0)) {
            $this->pesan = 'Poin tidak mencukupi untuk reward ini.';
            return false;
        }

        return true;
    }


    private function accRedeemPoint($memberrewardid = null)
    {
        // Ambil data user dan reward
        $user = UserModel::find($this->user_id);
        $reward = RewardModel::find($this->reward_id);

        // Validasi data user dan reward
        if (!$user) {
            throw new \Exception('User tidak ditemukan.');
        }

        if (!$reward) {
            throw new \Exception('Reward tidak ditemukan.');
        }

        $required_points = $reward->point; // Poin yang dibutuhkan dari reward
        $current_points = $user->points ?? 0; // Poin yang dimiliki user

        // Validasi apakah poin mencukupi sebelum pengurangan
        if ($current_points < $required_points) {
            throw new \Exception('Poin member tidak mencukupi untuk redeem reward ini.');
        }

        // Kurangi poin user
        $user->points -= $required_points;
        $user->save();

        // Siapkan data untuk tabel MemberPointModel
        $data = [
            'user_id' => $this->user_id,
            'member_reward_id' => $memberrewardid,
            'points' => $required_points * -1, // Poin yang dikurangi
            'notes' => 'Redeem point reward',
            'pengelola_user_id' => session('admin')->id,
            'created_at' => Carbon::now(),
        ];

        // Simpan atau update data ke MemberPointModel
        MemberPointModel::updateOrInsert(
            [
                'user_id' => $this->user_id,
                'member_reward_id' => $memberrewardid,
            ],
            $data
        );

        // Catat log
        LogController::writeLog(
            ValidatedPermission::UBAH_DATA_REDEEM_POINT,
            'Redeem Point disetujui',
            $data,
            0,
            $this->user_id
        );
    }



    private function retractRedeemPoint($memberrewardid)
    {
        $user = UserModel::find($this->user_id);

        // Validasi data user
        if (!$user) {
            throw new \Exception('User tidak ditemukan.');
        }

        // Ambil data dari MemberPointModel
        $memberPoint = MemberPointModel::where([
            'user_id' => $this->user_id,
            'member_reward_id' => $memberrewardid,
        ])->first();

        if ($memberPoint) {
            // Tambahkan kembali poin yang dikurangi
            $user->points += abs($memberPoint->points);

            // Pastikan nilai poin user tidak negatif
            if ($user->points < 0) {
                $user->points = 0;
            }

            $user->save();

            // Hapus log dari MemberPointModel
            $memberPoint->delete();

            // Catat log pengembalian poin
            LogController::writeLog(
                ValidatedPermission::UBAH_DATA_REDEEM_POINT,
                'Poin dikembalikan karena status berubah ke Ditolak',
                [],
                0,
                $this->user_id
            );
        }
    }



    public function store()
    {
        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $lastid = MemberRewardModel::query()->insertGetId($v);
            session()->flash('success', 'Data berhasil disimpan');

            if ($this->status == 0) {
                $this->accRedeemPoint($lastid);
            }

            $this->dispatch('refreshData');
        } catch (\Exception $e) {
            session()->flash('error', 'Data gagal disimpan: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            $lastid = $this->lm->id;
            MemberRewardModel::query()->where('id', $lastid)->update($v);

            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');
        } catch (\Exception $e) {
            session()->flash('error', 'Data gagal diubah: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.redeempoint.form');
    }

    private function returnPointsToUser()
    {
        $user = UserModel::find($this->user_id);

        if (!$user) {
            throw new \Exception('User tidak ditemukan.');
        }

        // Tambahkan poin yang telah dikurangi sebelumnya
        $user->points += $this->lm->reward_point ?? 0;
        $user->save();

        // Hapus catatan pengurangan poin dari MemberPointModel jika ada
        MemberPointModel::where([
            'user_id' => $this->user_id,
            'member_reward_id' => $this->lm->id,
        ])->delete();

        // Catat log
        LogController::writeLog(
            ValidatedPermission::UBAH_DATA_REDEEM_POINT,
            'Poin dikembalikan karena reward ditolak',
            [],
            0,
            $this->user_id
        );
    }
}
