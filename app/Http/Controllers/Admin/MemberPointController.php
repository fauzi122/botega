<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use App\Models\MemberPointModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MemberPointController extends Controller
{
    public function index()
    {
        // Kembalikan view untuk halaman DataTables
        return view('admin.member_points.table');
    }

    public function getData(Request $request)
    {
        $start = $request->start; // Offset dari DataTables
        $length = $request->length; // Jumlah data per halaman
        $search = $request->search['value']; // Nilai pencarian
        $orderColumnIndex = $request->order[0]['column']; // Index kolom untuk sorting
        $orderDirection = $request->order[0]['dir']; // Arah sorting: asc/desc
        $columns = ['id', 'first_name', 'email', 'points']; // Kolom-kolom pada tabel

        // Query utama
        $query = UserModel::query();

        // Filter pencarian
        if (!empty($search)) {
            $query->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        }

        // Total records sebelum filter
        $totalRecords = $query->count();

        // Sorting dan Pagination
        $query->orderBy($columns[$orderColumnIndex], $orderDirection)
            ->skip($start)
            ->take($length);

        $data = $query->get();

        // JSON Response
        return response()->json([
            'draw' => $request->draw, // Digunakan DataTables untuk sinkronisasi
            'recordsTotal' => $totalRecords, // Total semua data (sebelum filter)
            'recordsFiltered' => $totalRecords, // Total data setelah filter
            'data' => $data, // Data yang ditampilkan
        ]);
    }
    public function memberDetail($id)
    {
        $details = MemberPointModel::select(
            'transactions.nomor_so as nomor_so',
            'detail_transactions.notes as nama_barang',
            'detail_transactions.qty',
            'detail_transactions.sale_price',
            'detail_transactions.dpp_amount',
            'detail_transactions.retur_qty',
            \DB::raw('
        CASE 
            WHEN COALESCE(detail_retur_penjualan.return_amount, 0) = 0 
                 AND COALESCE(detail_transactions.retur_qty, 0) > 0 THEN
                ROUND((detail_transactions.dpp_amount / NULLIF(detail_transactions.qty, 0)) * detail_transactions.retur_qty, 2)
            ELSE
                COALESCE(detail_retur_penjualan.return_amount, 0)
        END as amount_retur
    '),
            \DB::raw('ROUND((
        detail_transactions.dpp_amount - 
        CASE 
            WHEN COALESCE(detail_retur_penjualan.return_amount, 0) = 0 
                 AND COALESCE(detail_transactions.retur_qty, 0) > 0 THEN
                (detail_transactions.dpp_amount / NULLIF(detail_transactions.qty, 0)) * detail_transactions.retur_qty
            ELSE
                COALESCE(detail_retur_penjualan.return_amount, 0)
        END
    ) / 1000, 0) as points')
        )
            ->join('transactions', 'transactions.id', '=', 'member_points.transaction_id')
            ->join('detail_transactions', 'detail_transactions.transaction_id', '=', 'transactions.id')
            ->leftJoin('detail_retur_penjualan', function ($join) {
                $join->on(DB::raw('detail_retur_penjualan.so_number COLLATE utf8mb4_unicode_ci'), '=', DB::raw('transactions.nomor_so COLLATE utf8mb4_unicode_ci'))
                    ->on(DB::raw('detail_retur_penjualan.product_id COLLATE utf8mb4_unicode_ci'), '=', DB::raw('detail_transactions.product_id COLLATE utf8mb4_unicode_ci'));
            })
            ->where('member_points.user_id', $id)
            ->whereNotNull('transactions.tgl_invoice')
            ->groupBy(
                'transactions.nomor_so',
                'detail_transactions.notes',
                'detail_transactions.qty',
                'detail_transactions.sale_price',
                'detail_transactions.dpp_amount',
                'detail_transactions.retur_qty',
                'detail_retur_penjualan.return_amount'
            )
            ->get();

        if ($details->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ], 503);
        }

        return response()->json([
            'status' => 'success',
            'details' => $details
        ]);
    }
}
