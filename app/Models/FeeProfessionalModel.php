<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class FeeProfessionalModel extends Model
{
    use HasFactory;
    protected $table = 'fee_professional';
    protected $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function view()
    {
        return FeeProfessionalModel::query()->from(function (Builder $b) {
            return $b->from('fee_professional as a')
                ->leftJoin('users as u', 'u.id', '=', 'a.member_user_id')
                ->leftJoin('proses_history as p', 'p.id', '=', 'a.proses_history_invoice_id')
                ->leftJoin('proses_history as psj', 'psj.id', '=', 'a.proses_history_nomor_sj')
                ->leftJoin('detail_transactions as dt', 'dt.id', '=', 'a.detail_transaction_id')
                ->leftJoin('transactions as trx', 'trx.id', '=', 'dt.transaction_id')
                ->leftJoin('users as utrx', 'utrx.id', '=', 'trx.member_user_id')
                ->leftJoin('products as pd', 'pd.id', '=', 'dt.product_id')
                ->leftJoin('fee_number as fn', 'fn.id', '=', 'a.fee_number_id')
                ->leftJoin('kategori_member as km', 'km.id', '=', 'u.kategori_id')
                ->leftJoin('product_categories as pc', 'pc.id', '=', 'pd.category_id')
                ->leftJoin('fee_dp  as dp', 'dp.fee_number_id', '=', 'fn.id')

                ->select([
                    'a.*',
                    'fn.nomor',
                    'u.id_no',
                    'u.first_name',
                    'u.last_name',
                    'p.history_number as invoice_number',
                    \DB::raw('concat(utrx.first_name," " ,utrx.last_name) as customer'),
                    \DB::raw('concat(u.first_name," " ,u.last_name) as member'),
                    'psj.history_number as sj_number',
                    "dt.item_disc_percent",
                    "km.name as kategori",
                    \DB::raw("SUBSTRING_INDEX(pd.name, ' ', 1) as merk"),
                    \DB::raw('concat(year(invoice_date), "-",month(invoice_date)) as periode'),
                    'dt.salesname',
                    'trx.nomor_so',
                    \DB::raw('COALESCE(fn.payment_made, 0) as payment_made'),
                    'dt.sale_price',
                    'dt.ppn',
                    'dt.qty',
                    'u.is_perusahaan',
                    'pd.name as product',
                    'dt.product_id as product_id',
                    'pc.category',
                    'pd.category_id',
                    'dp.number'
                ]);
        }, 'fee_professional');
    }


    public static function resume()
    {
        return self::view()->select([
            'fee_number_id',
            'nomor',
            'member_user_id',
            'id_no',
            'first_name',
            'last_name',
            'member',
            'product',
            'periode',
            'kode_merger',
            'npwp',
            'kategori',
            \DB::raw('max(payment_made) as payment_made'),
            \DB::raw('sum(dpp_amount) as dpp_amount'),
            \DB::raw('sum(fee_amount) as fee_amount'),
            'fee_percent',
            'pph_percent',
            \DB::raw('sum(pph_amount) as pph_amount'),
            \DB::raw('sum(total_pembayaran) - max(payment_made)  as total_pembayaran'),
            'is_perusahaan'
        ], 'fee_professional')->groupBy(['fee_number_id']);
    }

    public static function resumeRekening()
    {
        return self::view()->select([
            'fee_number_id',
            'nomor',
            'member_user_id',
            'id_no',
            'first_name',
            'last_name',
            'member',
            'product',
            'periode',
            'kode_merger',
            'npwp',
            'kategori',
            \DB::raw('max(payment_made) as payment_made'),
            \DB::raw('sum(dpp_amount) as dpp_amount'),
            \DB::raw('sum(fee_amount) as fee_amount'),
            'fee_percent',
            'pph_percent',
            \DB::raw('sum(pph_amount) as pph_amount'),
            \DB::raw('sum(total_pembayaran) - max(payment_made)  as total_pembayaran'),
            'is_perusahaan',
            'nama_bank',
            'no_rekening',
            'an_rekening',
            'no_faktur',
            'bank_kota'
        ], 'fee_professional')->groupBy(['fee_number_id']);
    }
    public static function resumeDP()
    {
        return self::view()->select([
            'fee_number_id',
            'nomor',
            'member_user_id',
            'id_no',
            'first_name',
            'last_name',
            'member',
            'product',
            'periode',
            'kode_merger',
            'npwp',
            'kategori',
            \DB::raw('max(payment_made) as payment_made'),
            \DB::raw('sum(dpp_amount) as dpp_amount'),
            \DB::raw('sum(fee_amount) as fee_amount'),
            'fee_percent',
            'pph_percent',
            \DB::raw('sum(pph_amount) as pph_amount'),
            \DB::raw('sum(total_pembayaran) - max(payment_made)  as total_pembayaran'),
            'is_perusahaan',
            'nama_bank',
            'no_rekening',
            'an_rekening',
            'no_faktur',
            'bank_kota',
            'number'
        ], 'fee_professional')->groupBy(['fee_number_id']);
    }


    public function detailTransaction()
    {
        return $this->belongsTo(DetailTransactionModel::class, 'detail_transaction_id', 'id');
    }
}
