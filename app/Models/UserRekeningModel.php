<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class UserRekeningModel extends Model
{
    use HasFactory;
    protected $table = 'user_rekenings';
    protected $fillable = ['*'];

    public static function view(){
        return UserRekeningModel::query()->from(function(Builder $b){
            return $b->from('user_rekenings as ur')
                     ->leftJoin('users as u', 'u.id', '=', 'ur.user_id')
                     ->leftJoin('bank as b', 'b.id','=', 'ur.bank_id')
                     ->select(['ur.*', 'u.first_name', 'u.last_name', 'u.id_no', 'b.name as bank', 'b.logo_path' ,'b.akronim as bank_akro']);
        }, 'user_rekenings');
    }
}
