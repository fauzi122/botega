<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class FeeNumberModel extends Model
{
    use HasFactory;
    protected $table = 'fee_number';
    protected $fillable = ['*'];
    protected $guarded = [];
    protected $primaryKey = 'id';

    public static function generateNomor(){
        $format = 'FM.' . date('ym') ;
        $r = static::query()->whereRaw('nomor LIKE ?', [$format.'%'])->orderBy('nomor','desc')->first();
        if($r == null){
            return $format . '0001';
        }

        $lastnumber = intval(substr($r->nomor,7));
        return $format . str_pad($lastnumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public static function view(){
        return static::query()->from(function(Builder $b){
            return $b->from('fee_number', 'f')
                     ->leftJoin('users as u', 'u.id', '=', 'f.member_user_id')
                     ->select(['f.*', 'u.first_name', 'u.last_name', 'u.email', 'u.is_perusahaan',
                            'u.id_no', 'u.npwp as npwpuser',
                             "dpp_penjualan as dpp_amount",
                             "f.fee as fee_amount",
                             "pph21 as pph_amount",
                             DB::raw("(total - coalesce(payment_made,0)) as total_pembayaran"),
                             "bank as nama_bank",
                         ]);
        }, 'fee_number');
    }



    public static function rekapNilaiFee($idfee){
        try {
            DB::update("UPDATE fee_number AS a
                            LEFT JOIN (
                                SELECT
                                    fee_number_id,
                                    SUM(dpp_amount) AS dpp_amount,
                                    SUM(total_pembayaran) AS total_pembayaran,
                                    SUM(fee_amount) AS fee_amount,
                                    SUM(pph_amount) AS pph_amount
                                FROM fee_professional
                                GROUP BY fee_number_id
                            ) AS b ON a.id = b.fee_number_id
                            SET
                                a.total = COALESCE(b.total_pembayaran, 0),
                                a.fee = COALESCE(b.fee_amount, 0),
                                a.`pph21` = COALESCE(b.pph_amount, 0),
                                a.`dpp_penjualan`= COALESCE(b.dpp_amount, 0)
                            WHERE
                                a.id = ?;", [$idfee]);
        }catch (\Exception $e){}

        try {
            DB::update("UPDATE fee_number AS fn
                            LEFT JOIN (
                                SELECT fee_number_id,
                                       GROUP_CONCAT(distinct DATE_FORMAT(invoice_date, '%b %y') ORDER BY invoice_date ASC SEPARATOR ', ') AS invoice_dates
                                FROM fee_professional
                                GROUP BY fee_number_id
                            ) fp ON fn.id = fp.fee_number_id
                        SET fn.periode = fp.invoice_dates
                        where fn.id=?", [$idfee]);
        }catch (\Exception $e){}
    }

    public static function updateRekeningPengajuan(){
        DB::update("UPDATE fee_number as fe
                                left join user_rekenings ur on fe.member_user_id = ur.user_id
                                left join bank b on ur.bank_id = b.id
                            set fe.no_rekening=coalesce( ur.no_rekening, ''),
                                fe.bank=coalesce( b.akronim, ''),
                                fe.an_rekening=coalesce( ur.an, ''),
                                fe.kode_bank=coalesce( b.kode_bank, ''),
                                fe.bank_kota=coalesce( ur.bank_kota, '')
                            where ur.is_primary=1 AND (fe.dt_pengajuan is not null) and (fe.dt_finish is null)");

        DB::update("UPDATE fee_professional as fe
                                left join user_rekenings ur on fe.member_user_id = ur.user_id
                                left join bank b on ur.bank_id = b.id
                            set fe.no_rekening=coalesce( ur.no_rekening, ''),
                                fe.nama_bank=coalesce( b.akronim, ''),
                                fe.an_rekening=coalesce( ur.an, ''),
                                fe.bank_kota=coalesce( ur.bank_kota, '')
                            where ur.is_primary=1 AND (fe.dt_pengajuan is not null) and (fe.dt_finish is null)");
    }

    public static function fixData(){
        DB::delete("delete from fee_number where id not in(SELECT fee_professional.fee_number_id from fee_professional)");
    }

}
