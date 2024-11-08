<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class FeePaymentMadeModel extends Model
{
    use HasFactory;
    protected $table = 'fee_payment_made';
    protected $fillable = ['*'];
    protected $guarded = ['*'];

    public static function view(){
        return static::from(function(Builder $b){
            return $b->from('fee_payment_made', 'fpm')
                     ->leftJoin('users as u','u.id', '=', 'fpm.member_user_id')
                     ->select(['fpm.*', 'u.first_name', 'u.last_name', 'u.email', 'u.id_no']);
        }, 'fee_payment_made');
    }


    public static function hitungPaymentMade($feenumberid){
        $f = FeeNumberModel::query()->where('id', $feenumberid)
            ->whereNull(['dt_acc'])
            ->first();
        if($f == null)return;
        $paymentmade = static::query()
            ->where('member_user_id', $f->member_user_id)
            ->where('nominal', '>', 0)
            ->where('nominal_hutang', '<', DB::raw('nominal'))
            ->sum(DB::raw('nominal - nominal_hutang'));
        $f->payment_made = $paymentmade;
        $f->total = $f->fee - doubleval($f->pph21) - doubleval($f->payment_made) - doubleval($f->pengurang);

        if($f->save()){
            $ff = static::query()
                ->where('member_user_id', $f->member_user_id)
                ->where('nominal', '>', 0)
                ->where('nominal_hutang', '<', DB::raw('nominal'))
                ->get();
            foreach ($ff as $fx){
                $fx->nominal_hutang = $fx->nominal;
                $fx->fee_number_id = $feenumberid;
                $fx->save();
            }
        }

    }

    public static function removePaymentMade($feenumberid){
        $fee = FeeNumberModel::query()->where('id', $feenumberid)->first();
        if($fee != null){
            $feepaid = static::query()->where('fee_number_id', $feenumberid)->get();
            foreach ($feepaid as $fp){
                $fp->nominal_hutang = 0;
                $fp->fee_number_id = null;
                $fp->save();
            }
            $fee->payment_made = 0;
            $fee->total = $fee->fee - doubleval($fee->pph21) - doubleval($fee->payment_made) - doubleval($fee->pengurang);
            $fee->save();
        }
    }
}
