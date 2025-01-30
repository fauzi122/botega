<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncPenjualanJob;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\DetailTransactionModel;
use App\Models\LogsModel;
use App\Models\ProsesHistoryModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenjualanProdukController extends Controller
{


    public function index()
    {
        return view('admin.penjualan.table');
    }

    public function form()
    {
        return view('admin.penjualan.form', [
            'transaction' => new TransactionModel()
        ]);
    }

    public function resync()
    {
        SyncPenjualanJob::dispatch(Carbon::now()->subDays(3)->format('d/m/Y'), false)->onConnection('sync');
    }

    public function detail($id)
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PENJUALAN)) {
            return;
        }
        $t = TransactionModel::view()->where('id', $id)->first();
        if ($t == null) {
            abort(404);
        }
        return view('admin.penjualan.form', [
            'transaction' => $t
        ]);
    }

    public function json_detail($id)
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PENJUALAN)) {
            return;
        }

        $t = TransactionModel::view()->where('id', $id)->first();
        return response()->json([
            'data' => $t
        ], status: $t == null ? 404 : 200);
    }


    public function datasource_detail()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PENJUALAN)) {
            return;
        }

        $id = \request('id');
        return datatables(
            DetailTransactionModel::view()
                ->where('transaction_id', $id)
        )->make(true);
    }

    public function datasource()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PENJUALAN)) {
            return;
        }

        return datatables(TransactionModel::view())
            ->editColumn('trx_at', function ($r) {
                if ($r['trx_at'] == null || $r['trx_at'] == '') return '';

                return Carbon::parse($r['trx_at'])->translatedFormat('d F Y');
            })
            ->toJson();
    }

    public function delete()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_PENJUALAN)) {
            return;
        }

        $id = \request('id');
        $r = TransactionModel::query()->whereIn('id', $id)->delete();
        return response()->json([
            'data' => $r
        ]);
    }

    public function delete_detail()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_PENJUALAN)) {
            return;
        }

        $id = \request('id');
        $r = DetailTransactionModel::query()->whereIn('id', $id)->delete();
        return response()->json([
            'data' => $r
        ]);
    }



    public function select2nomor_so()
    {
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(
            TransactionModel::query()
                ->whereNotIn('status', ['QUEUE', 'DRAFT', 'UNAPPROVED'])
                ->orderBy('trx_at', 'desc'),
            ['nomor_so'],
            $q
        )->paginate(10);
        $ret[] = ['id' => '', 'text' => '--'];
        foreach ($r as $k) {
            $ret[] = ['id' => $k->id, 'text' => $k->nomor_so . ' | <small class="text-muted">  ' . $k->trx_at . ' ( IDR ' . number_format($k->total) . ' )</small> '];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }
    public function select2nomor_so_fee()
    {
        $q = \request('q');
        $memberId = \request('member_id'); // Ambil ID customer jika ada

        // Query dasar untuk mengambil SO berdasarkan kondisi
        $query = TransactionModel::query()
            ->whereNull('tgl_invoice')
            ->orderBy('trx_at', 'desc');

        // Jika member_id dikirimkan, ambil SO milik member tersebut
        if (!empty($memberId)) {
            $query->where('member_user_id', $memberId);
        } else {
            // Jika tidak ada member_id, ambil SO milik user yang sedang login
            $query->where('member_user_id', session('admin')->id);
        }

        $r = QueryBuilderExt::whereFilter($query, ['nomor_so'], $q)->paginate(10);

        $ret[] = ['id' => '', 'text' => '--'];
        foreach ($r as $k) {
            $ret[] = [
                'id' => $k->nomor_so,
                'text' => $k->nomor_so . ' | <small class="text-muted">' .
                    $k->trx_at . ' ( IDR ' . number_format($k->total) . ' )</small> '
            ];
        }

        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }


    public function select2invoice()
    {
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(
            ProsesHistoryModel::query()
                ->whereRaw('LEFT(history_number,2)=?', ['IN'])
                ->orderBy('history_date', 'desc'),
            ['history_number', 'history_date'],
            $q
        )->paginate(10);
        $ret[] = ['id' => '', 'text' => '--'];
        foreach ($r as $k) {
            $ret[] = ['id' => $k->id, 'text' => $k->history_number . ' | <small class="text-muted">  ' . $k->history_date . ' ( IDR ' . number_format($k->history_amount) . ' )</small> '];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }
}
