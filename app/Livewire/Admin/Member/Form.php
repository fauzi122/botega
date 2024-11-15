<?php

namespace App\Livewire\Admin\Member;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\CabangModel;
use App\Models\LevelMemberModel;
use App\Models\MemberPointModel;
use App\Models\RequestUpdateModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;
    protected $listeners = ['edit', 'newForm'];

    public $nik;
    public $npwp;
    public $id_no;
    public $first_name;
    public $last_name;
    public $user_type;
    public $gender;
    public $birth_at;
    public $home_addr;
    public $phone;
    public $hp;
    public $fax;
    public $web;
    public $nppkp;
    public $email;
    public $wa;
    public $foto_path;
    //    public $role_id;
    public $rt, $rw, $zip_code;
    public $level_member_id;
    public $editform = false;
    public $lm;
    //    public $roles;
    public $levelmember;
    public $urlfoto;
    public $reward_type;
    public $cabang_id;
    public $points;
    public $cabang;
    public $is_perusahaan;
    public $clearfoto = false;
    public $id;
    public $is_email_verified;
    public $emailLama;

    public function mount()
    {
        //        $this->roles = RoleModel::get();
        $this->levelmember = LevelMemberModel::get();
        $this->cabang = CabangModel::get();
    }

    public function clearFoto()
    {
        $this->foto_path = null;
        $this->urlfoto = '';
        $this->clearfoto = true;
    }

    public function edit($id)
    {
        $this->lm = UserModel::query()->where('user_type', 'member')->find($id);
        $this->id = $this->lm?->id;
        $this->emailLama = $this->lm?->email;
        $this->editform = $this->lm != null;
        $this->npwp = $this->lm?->npwp;
        $this->nik = $this->lm?->nik;
        $this->id_no = $this->lm?->id_no ?? $this->generate();
        $this->first_name = $this->lm?->first_name ?? '';
        $this->last_name = $this->lm?->last_name ?? '';
        $this->user_type = $this->lm?->user_type ?? 'member';
        $this->gender = $this->lm?->gender ?? 'L';
        $this->birth_at = $this->lm?->birth_at;
        $this->home_addr = $this->lm?->home_addr ?? "-";
        $this->phone = $this->lm?->phone ?? "-";
        $this->hp = $this->lm?->hp ?? '-';
        $this->email = $this->lm?->email ?? "";
        $this->web = $this->lm?->web ?? "";
        $this->fax = $this->lm?->fax ?? "";
        $this->nppkp = $this->lm?->nppkp ?? "";
        $this->wa = $this->lm?->wa ?? "-";
        $this->rt = $this->lm?->rt ?? "-";
        $this->rw = $this->lm?->rw ?? "-";
        $this->zip_code = $this->lm?->zip_code ?? "-";
        //        $this->role_id = $this->lm?->role_id;
        $this->foto_path = $this->lm?->foto_path ?? '';
        $this->level_member_id = $this->lm?->level_member_id;
        $this->reward_type = $this->lm?->reward_type;
        $this->cabang_id = $this->lm?->cabang_id;
        $this->points = $this->lm?->points;
        $this->clearfoto = false;
        $this->is_perusahaan = $this->lm?->is_perusahaan;
        $this->is_email_verified = ($this->lm?->date_verify_email == null) ? 0 : 1;

        $this->urlfoto = '';
        if (Storage::exists($this->foto_path) && $this->foto_path != '') {
            $this->urlfoto = url('admin/member/foto/' . $id);
        }
        $this->dispatch('render');
    }

    private function generate() {}

    public function newForm()
    {
        $this->edit(0);
    }

    private function validasi()
    {
        $v =   $this->validate([
            'id_no' => 'required|unique:users,id_no,' . $this->lm?->id,
            //            'nik' => 'unique:users,nik,'.$this->lm?->id,
            'first_name' => 'required|min:3',
            'gender' => 'required',
            //            'birth_at' => 'date',
            //            'home_addr' => 'required',
            //            'hp' => 'required',
            //            'wa' => 'required',
            'email' => 'email|unique:users,email,' . $this->lm?->id,
            //            'role_id' => 'required',
            'level_member_id' => 'required',
            //            'cabang_id' => 'exists:cabang,id',
            'foto_path' => (is_object($this->foto_path) == false  ? '' : 'image|') . 'max:2048'
        ], [
            'id_no' => [
                'unique' => 'ID Member sudah digunakan'
            ],
            'first_name' => [
                'required' => 'Nama harus diisikan',
                'min' => 'Nama  minimal 3 karakter'
            ],
            'gender' => 'Jenis kelamin harus diisikan',
            'birth_at' => 'Tanggal lahir harus diisi',
            'home_addr' => 'Alamat harus diisi',
            'email' => [
                'required' => 'Alamat email harus diisi',
                'unique' => 'Alamat email ini sudah ada digunakan'
            ],
            'cabang_id' => 'Cabang yang dipilih tidak tersedia',
            'hp' => 'No HP harus diisi',
            'wa' => 'No WA harus diisi',
            //            'role_id' => 'Peran member harus diisi',
            'level_member_id' => 'Level member harus diisi',
            'foto_path' => [
                'image' => 'Format harus gambar',
                'max' => 'Maximal foto 2 MB'
            ]
        ]);
        $v['nik'] = $this->nik;
        $v['npwp'] = $this->npwp;
        $v['web'] = $this->web;
        $v['fax'] = $this->fax;
        $v['nppkp'] = $this->nppkp;
        $v['last_name'] = $this->last_name;
        $v['user_type'] = $this->user_type;
        $v['rt'] = $this->rt;
        $v['rw'] = $this->rw;
        $v['phone'] = $this->phone;
        $v['zip_code'] = $this->zip_code;
        $v['points'] = $this->points;
        $v['reward_type'] = $this->reward_type;
        $v['is_perusahaan'] = $this->is_perusahaan;
        $v['birth_at'] = strlen($this->birth_at) < 10 ? null : Carbon::parse($this->birth_at)?->format('Y-m-d');
        $v['home_addr'] = $this->home_addr;
        $v['hp'] = $this->hp;
        $v['wa'] = $this->wa;
        $v['cabang_id'] = (int)$this->cabang_id;
        $v['cabang_id'] = $v['cabang_id'] == 0 ? null : $v['cabang_id'];

        if ($this->emailLama != $v['email']) {
            $v['date_verify_email'] = null;
        }

        return $v;
    }

    public function save()
    {
        if ($this->editform) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function store()
    {
        $v = $this->validasi();
        $v['created_at'] = Carbon::now();
        dd($v);
        try {
            $v['date_verify_email'] = $this->is_email_verified == "1" ? Carbon::now() : null;
            $m = UserModel::insert($v);
            $this->savePoto();

            $this->edit(0);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refreshData');
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_MEMBER, 'Menambah data member', $v);
        } catch (\Exception $e) {
            session()->flash('error', 'Data Gagal disimpan');
        }
    }

    private function savePoto()
    {
        if (is_object($this->foto_path)) {
            $id = $this->lm?->id ?? DB::getPdo()->lastInsertId();
            $newname = $this->foto_path->storeAs('photo', $id . '.png');
            UserModel::query()->where('id', $id)->update(['foto_path' => $newname]);
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_MEMBER, 'Menyimpan foto baru', $id);
        } else if ($this->clearfoto) {
            $this->hapusFoto();
        }
    }

    public function update()
    {
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            if ($this->lm?->date_verify_email == null && $this->is_email_verified == "1") {
                $v['date_verify_email'] = Carbon::now();
            } else if ($this->is_email_verified == "0") {
                $v['date_verify_email'] = null;
            }
            $npwpUpdated = $this->lm->npwp !== $v['npwp'];
            $isPerusahaanUpdated = $this->lm->is_perusahaan !== $v['is_perusahaan'];

            $m = UserModel::query()->where('id', $this->lm->id)->update($v);

            // Jika NPWP atau is_perusahaan berubah, jalankan update pada fee_professional dan fee_number
            if ($npwpUpdated || $isPerusahaanUpdated) {
                $user = UserModel::find($this->lm->id);
                $user->updateFeeRelatedCalculations(); // Panggil metode yang mengatur pembaruan terkait
            }

            $this->savePoto();

            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refreshData');

            LogController::writeLog(ValidatedPermission::UBAH_DATA_MEMBER, 'Merubah data Member', $v);
        } catch (\Exception $e) {
            session()->flash('error', 'Data Gagal diubah' . $e->getMessage());
        }
    }

    private function hapusFoto()
    {
        try {
            Storage::delete($this->lm?->foto_path ?? '');
            UserModel::query()->where('id', $this->lm?->id)->update(['foto_path' => '']);
            LogController::writeLog(ValidatedPermission::UBAH_DATA_MEMBER, 'Hapus foto member', [
                'path' => $this->lm?->foto_path,
                'id' => $this->lm?->id
            ]);
        } catch (\Exception $e) {
        }
    }

    public function render()
    {
        return view('livewire.admin.member.form');
    }
}
