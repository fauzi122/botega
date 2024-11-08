<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class TransactionProfessionalModel extends Model
{
    use HasFactory;
    protected $table = 'transactions_professional';
    protected $fillable = ['*'];

    public static function view(){
        return TransactionProfessionalModel::query()->from(function(Builder $b){
            return $b->from('transactions_professional as b')
                     ->leftJoin('users as u', 'u.id', '=', 'b.professional_user_id')
                     ->leftJoin('roles as r', 'r.id', '=', 'b.role_id')
                     ->leftJoin('transactions as t', 't.id', '=', 'b.transaction_id')
                     ->select(['b.*', 'u.first_name', 'u.last_name', 'u.id_no', 'r.name as role','t.invoice_no', 't.total', 't.trx_at']);
        }, 'transactions_professional');
    }
}
