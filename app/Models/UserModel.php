<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserModel extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';

    protected $hidden = [
        'token_reset',
        'sandi'
    ];

    protected $casts = [
        'code_verify_email_expire' => 'datetime',
        'code_verify_nohp_expire' => 'datetime',
        'date_verify_email' => 'datetime',
        'date_verify_nohp' => 'datetime',
        'sandi' => 'hashed',
    ];

    public static function view()
    {
        return UserModel::query()->from(function (Builder $b) {
            return $b->from('users as a')
                ->leftJoin('level_member as b', 'a.level_member_id', '=', 'b.id')
                ->leftJoin('kategori_member as km', 'km.id', '=', 'a.kategori_id')
                ->leftJoin('roles as r', 'a.role_id', '=', 'r.id')
                ->select(['a.*', 'b.level_name', 'b.level', 'r.name as role', 'km.name as kategori']);
        }, 'users');
    }
    // protected static function booted()
    // {
    //     static::updated(function ($user) {
    //         // Jalankan `updateFeeRelatedCalculations` jika `npwp` atau `is_perusahaan` berubah
    //         if ($user->isDirty('npwp') || $user->isDirty('is_perusahaan')) {
    //             $user->updateFeeRelatedCalculations();
    //         }
    //     });
    // }

    public function updateFeeRelatedCalculations()
    {
        // Mendapatkan persentase PPH (21 atau 23) berdasarkan status `is_perusahaan` dan `npwp`
        $pphPercent = $this->calculatePphPercent();

        // Ambil semua FeeNumber yang dt_finish nya masih kosong
        $feeNumbers = FeeNumberModel::where('member_user_id', $this->id)
            ->whereNull('dt_finish')
            ->get();

        // Jika tidak ada FeeNumber yang dt_finish nya kosong, maka hentikan proses
        if ($feeNumbers->isEmpty()) {
            return;
        }

        foreach ($feeNumbers as $feeNumber) {
            // Ambil semua FeeProfessional yang dt_finish nya masih kosong
            $feeProfessionals = FeeProfessionalModel::where('fee_number_id', $feeNumber->id)
                ->whereNull('dt_finish')
                ->get();

            foreach ($feeProfessionals as $fee) {
                // Perbarui `pph_percent` dan `pph_amount` berdasarkan apakah perusahaan atau individu
                $fee->npwp = $this->npwp;
                $fee->pph_percent = $pphPercent;
                $fee->pph_amount = $fee->fee_amount * ($pphPercent / 100);
                $fee->total_tagihan = $fee->fee_amount - $fee->pph_amount;
                $fee->total_pembayaran = $fee->total_tagihan * ($fee->percentage_fee / 100);
                $fee->harus_dibayar = $fee->fee_amount - $fee->pph_amount;
                $fee->save();

                // Pembaruan `fee_split` untuk memastikan pembagian berdasarkan `fee_professional`
                FeeSplitModel::splitFee($fee->id, 1);
            }

            // Update `fee_number` dengan hanya satu PPH yang relevan (21 atau 23)
            $feeNumber->npwp = $this->npwp;

            if ($this->is_perusahaan) {
                // Perusahaan: hanya isi `pph23`, kosongkan `pph21`
                $feeNumber->pph21 = 0;
                $feeNumber->pph23 = $feeNumber->fee * ($pphPercent / 100);
            } else {
                // Individu: hanya isi `pph21`, kosongkan `pph23`
                $feeNumber->pph21 = $feeNumber->fee * ($pphPercent / 100);
                $feeNumber->pph23 = 0;
            }

            $feeNumber->total = $feeNumber->fee - $feeNumber->pph21 - $feeNumber->pph23;
            $feeNumber->save();
        }
    }



    public function calculatePphPercent()
    {
        // Jika `is_perusahaan` bernilai `1`, artinya pengguna adalah perusahaan
        if ($this->is_perusahaan == 1) {
            return !empty(trim($this->npwp)) ? 2 : 4; // Perusahaan: 2% dengan NPWP, 4% tanpa NPWP
        }

        // Jika `is_perusahaan` bernilai `0`, artinya pengguna adalah individu
        return !empty(trim($this->npwp)) ? 2.5 : 3; // Individu: 2.5% dengan NPWP, 3% tanpa NPWP
    }

    public function memberPoints()
    {
        return $this->hasMany(MemberPointModel::class, 'user_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionModel::class, 'member_user_id', 'id');
    }
}
