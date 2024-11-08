<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ReturPenjualanModel extends Model
{
    use HasFactory;
    protected $table = 'retur_penjualan';
    protected $fillable = ['*'];
    protected $guarded = [];


    public static function view(){
        return static::from(function(Builder $v){
            return $v->from('retur_penjualan', 'r')
                     ->leftJoin('users as u', 'u.id', '=', 'r.member_user_id')
                     ->leftJoin('users as fu', 'fu.id', '=', 'r.fee_user_id')
                     ->select(['r.*', 'u.first_name', 'u.last_name', 'u.id_no',
                            'fu.first_name as first_name_pro', 'fu.last_name as last_name_pro',
                            'fu.id_no as id_no_pro'
                         ]);
        }, 'retur_penjualan');
    }
}
