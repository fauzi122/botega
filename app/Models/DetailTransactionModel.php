<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class DetailTransactionModel extends Model
{
    use HasFactory;
    protected $table = 'detail_transactions';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function view(){
        return DetailTransactionModel::query()->from(function(Builder $b){
            return $b->from('detail_transactions as d')
                     ->leftJoin('transactions as trx', 'trx.id', '=', 'd.transaction_id')
                     ->leftJoin('products as p', 'p.id', '=', 'd.product_id')
                     ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
                     ->selectRaw('trx.trx_at, d.*, p.kode, p.name, "DT" as type,
                        p.price as latest_product_price,
                        p.qty as stok_produk,
                        p.category_id,
                        pc.category ');


        }, 'detail_transactions');
    }

    public static function getSOProductNull(){
        return DetailTransactionModel::query()->from(function(Builder $b){
            return $b->from('detail_transactions as d')
                ->leftJoin('transactions as trx', 'trx.id', '=', 'd.transaction_id')
                ->whereNull('d.product_id')
                ->select(['trx.nomor_so']);
        }, 'detail_transactions');
    }
}
