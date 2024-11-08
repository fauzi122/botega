<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class FeeSplitModel extends Model
{
    use HasFactory;
    protected $table = 'fee_split';
    protected $guarded = [];
    protected $fillable = ['*'];

    public static function splitFee($idfee, $numSplit=1){
        $fpm = FeeProfessionalModel::query()->where('id', $idfee)->first();
        if($fpm == null)return false;

        $data = [
            'fee_professional_id' => $fpm->id,
            'fee_total' => $fpm->total_tagihan,
            'fee_outstanding' => $fpm->total_tagihan - $fpm->total_pembayaran,
            'num_split' => $numSplit,
            'percentage' => $fpm->percentage_fee,
            'fee_paid' => $fpm->total_pembayaran,
            'member_user_id' => $fpm->member_user_id,
            'detail_transaction_id' => $fpm->detail_transaction_id,
            'detail_delivery_order_id' => $fpm->detail_delivery_id
        ];

        $fs = FeeSplitModel::query()->where([
            'fee_professional_id'=> $idfee,
            'num_split' => $numSplit
        ])->first();
        $parentid = null;
        if($fs == null){
            $data['created_at'] = Carbon::now();
            $parentid = FeeSplitModel::query()->insertGetId($data);
        }else{
            $data['updated_at'] = Carbon::now();
            FeeSplitModel::query()->where([
                'fee_professional_id'=> $idfee,
                'num_split' => $numSplit
            ])->update($data);
            $parentid = $fs->id;
        }

        $percent = $data['percentage'];
        if($percent < 100){

            $data = [
                'fee_total' => $fpm->total_tagihan,
                'fee_outstanding' => 0,
                'num_split' => 2,
                'percentage' => 100 - $percent,
                'fee_paid' =>  $fpm->total_tagihan - $fpm->total_pembayaran,
                'member_user_id' => $fpm->member_user_id,
                'detail_transaction_id' => $fpm->detail_transaction_id,
                'detail_delivery_order_id' => $fpm->detail_delivery_id,
                'parent_id' => $parentid,
            ];

            $fs = FeeSplitModel::query()->where([
                'member_user_id' => $fpm->member_user_id,
                'detail_transaction_id' => $fpm->detail_transaction_id,
                'detail_delivery_order_id' => $fpm->detail_delivery_id,
                'num_split' =>2
            ])->first();
            if($fs == null){
                $data['created_at'] = Carbon::now();
                FeeSplitModel::query()->insertGetId($data);
            }else{
                $data['updated_at'] = Carbon::now();
                FeeSplitModel::query()->where([
                    'member_user_id' => $fpm->member_user_id,
                    'detail_transaction_id' => $fpm->detail_transaction_id,
                    'detail_delivery_order_id' => $fpm->detail_delivery_id,
                    'num_split' =>2
                ])->update($data);
            }
        }else{
            FeeSplitModel::query()->where([
                'member_user_id' => $fpm->member_user_id,
                'detail_transaction_id' => $fpm->detail_transaction_id,
                'detail_delivery_order_id' => $fpm->detail_delivery_id,
                'num_split' =>2
            ])->delete();
        }
    }

    public static function hasSplit($member_user_id, $dt_id, $dd_id){
        $kondisi = [
            'member_user_id' => $member_user_id,
            'detail_transaction_id' => $dt_id,
            'detail_delivery_order_id' => $dd_id,
        ];
        if($dt_id==null){
            unset($kondisi['detail_transaction_id']);
        }
        if($dd_id==null){
            unset($kondisi['detail_delivery_order_id']);
        }
        $r =  static::query()->where($kondisi)->orderBy('num_split','asc')->get();
        if($r->count() <= 1)return false;

        return $r->first;
    }

    public static function outstandingFee(){
        return static::from(function(Builder $b){
            return $b->from('fee_split', 'fs')
                        ->leftJoin('users as u', 'u.id','=','fs.member_user_id')
                        ->leftJoin('detail_transactions as dt','dt.id','=','fs.detail_transaction_id')
                        ->leftJoin('transactions as trx','trx.id','=','dt.transaction_id')
                        ->leftJoin('detail_delivery_order as ddo','ddo.id','=','fs.detail_delivery_order_id')
                        ->leftJoin('detail_transactions as dtdd','dtdd.id','=','ddo.detail_transaction_id')
                        ->leftJoin('transactions as trxdd','trxdd.id','=','dtdd.transaction_id')
                        ->leftJoin('fee_split as fe2','fs.parent_id','=','fe2.id')
                        ->leftJoin('fee_professional as fp', 'fp.id', '=', 'fe2.fee_professional_id')
                        ->leftJoin('fee_number as fn','fn.id','=','fp.fee_number_id')
                        ->whereNull('fs.fee_professional_id')->where('fe2.fee_outstanding','>', 0)
                        ->select(['fs.*', 'fn.nomor', 'u.id_no', 'u.first_name', 'u.last_name', 'trxdd.trx_at', 'dt.notes as dtnotes',
                            'dtdd.notes as dtddnotes', 'trx.nomor_so as so_dt', "fn.id as fee_number_id",
                            'trxdd.nomor_so as so_trx', 'fe2.fee_outstanding as os']);
        }, 'fee_split');
    }

    public static function normalizeFeeProfessional(){
        \DB::delete("delete from fee_split where fee_professional_id NOT IN(SELECT id FROM fee_professional)");
    }
}
