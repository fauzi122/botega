<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class TransactionModel extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function view(){
        return TransactionModel::query()->from(function(Builder $b){
            return $b->from('transactions as t')
                     ->leftJoin('users as u', 'u.id','=','t.member_user_id')
                     ->leftJoin('users as p', 'p.id', '=', 't.pengelola_user_id')
                     ->selectRaw('t.*, u.id_no as no_member, u.first_name as member, u.last_name, u.id_no, p.first_name as admin');
        }, 'transactions');
    }
}
