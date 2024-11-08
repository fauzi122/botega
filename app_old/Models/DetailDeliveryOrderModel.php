<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class DetailDeliveryOrderModel extends Model
{
    use HasFactory;
    protected $table = 'detail_delivery_order';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $fillable = ['*'];


    public static function view(){
        return DetailTransactionModel::query()->from(function(Builder $b){
            return $b->from('detail_delivery_order as dd')
                ->leftJoin('detail_transactions as d', 'dd.detail_transaction_id', '=', 'd.id')
                ->leftJoin('products as p', 'p.id', '=', 'd.product_id')
                ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
                ->leftJoin('transactions as trx', 'trx.id', '=', 'd.transaction_id')
                ->selectRaw('trx.trx_at, dd.id, dd.number_sj, dd.number_in, dd.status_claim, d.status_claim as d_s_c,
                    d.transaction_id, dd.detail_transaction_id, d.sale_price,
                    (d.discount / d.qty * dd.process_qty) as discount,
                    dd.process_qty as qty, dd.unit, (dd.process_qty * (d.total_price/d.qty) ) as total_price,
                    (dd.dpp_amount_unit * dd.process_qty) as dpp_amount, "DD" as type, dd.retur_no, dd.retur_qty,
                    p.kode, p.name, p.price as latest_product_price, p.qty as stok_produk, p.category_id, pc.category ');

        }, 'detail_transactions');
    }
}
