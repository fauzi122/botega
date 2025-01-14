<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\MemberRewardModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedeemController extends Controller
{
    /**
     * Halaman utama (view index).
     */
    public function index()
    {
        // Contoh menandakan log sudah dibaca, dll.
        // LogsModel::whereRaw('actions LIKE ?', ['Klaim reward%'])->update(['status' => 1]);
        return view('admin.redeempoint.table');
    }

    /**
     * Hitung total record di setiap step.
     * step = 0 (Baru diajukan),
     * step = 1 (Proses),
     * step = 2 (Disetujui),
     * step = 3 (Ditolak)
     */
    public function countTab($step = 0)
    {
        $count = MemberRewardModel::query()
            ->where('status', $step)
            ->count();

        return $count;
    }

    /**
     * DataTable untuk status = 0 (baru diajukan).
     */
    public function datasource()
    {
        Carbon::setLocale('id');
        // status = 0 artinya Baru diajukan
        $q = MemberRewardModel::view()->where('status', 0);

        return datatables($q)
            ->addColumn('member', function ($b) {
                return $b['first_name'] . ' ' . $b['last_name'];
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row['created_at'])->translatedFormat('l, d M Y');
            })
            ->toJson(true);
    }

    /**
     * DataTable untuk status = 1 (proses).
     */
    public function datasourceProses()
    {
        Carbon::setLocale('id');
        $q = MemberRewardModel::view()->where('status', 1);

        return datatables($q)
            ->addColumn('member', function ($b) {
                return $b['first_name'] . ' ' . $b['last_name'];
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row['created_at'])->translatedFormat('l, d M Y');
            })
            ->toJson(true);
    }

    /**
     * DataTable untuk status = 2 (disetujui).
     */
    public function datasourceAcc()
    {
        Carbon::setLocale('id');
        $q = MemberRewardModel::view()->where('status', 2);

        return datatables($q)
            ->addColumn('member', function ($b) {
                return $b['first_name'] . ' ' . $b['last_name'];
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row['created_at'])->translatedFormat('l, d M Y');
            })
            ->toJson(true);
    }

    /**
     * DataTable untuk status = 3 (ditolak).
     */
    public function datasourceTolak()
    {
        Carbon::setLocale('id');
        $q = MemberRewardModel::view()->where('status', 3);

        return datatables($q)
            ->addColumn('member', function ($b) {
                return $b['first_name'] . ' ' . $b['last_name'];
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row['created_at'])->translatedFormat('l, d M Y');
            })
            ->toJson(true);
    }

    /**
     * Mengubah status dari 'Baru diajukan' (0) ke 'Proses' (1).
     */
    public function statusDraft(Request $request)
    {
        // $request->id => array of ID
        $ids = $request->input('id');
        $updated = MemberRewardModel::whereIn('id', $ids)
            ->where('status', 1)
            ->update(['status' => 0]);

        // Contoh catat log
        // LogController::writeLog(...);

        return response()->json([
            'data' => $updated
        ]);
    }

    public function statusPengajuan(Request $request)
    {
        // $request->id => array of ID
        $ids = $request->input('id');
        $updated = MemberRewardModel::whereIn('id', $ids)
            ->whereIn('status', [0, 2])
            ->update(['status' => 1]);

        // Contoh catat log
        // LogController::writeLog(...);

        return response()->json([
            'data' => $updated
        ]);
    }

    /**
     * Mengubah status dari 'Proses' (1) ke 'Disetujui' (2).
     */
    public function statusAcc(Request $request)
    {
        $ids = $request->input('id');
        $updated = MemberRewardModel::whereIn('id', $ids)
            ->where('status', 1)
            ->update(['status' => 2]);

        return response()->json([
            'data' => $updated
        ]);
    }

    /**
     * Mengubah status menjadi 'Ditolak' (3).
     * (Bisa dipanggil dari tab mana saja, misal dari Baru diajukan atau Proses)
     */
    public function statusTolak(Request $request)
    {
        $ids = $request->input('id');
        $updated = MemberRewardModel::whereIn('id', $ids)
            ->whereIn('status', [0]) // misal hanya boleh tolak dari status 0,1,2
            ->update(['status' => 3]);

        return response()->json([
            'data' => $updated
        ]);
    }

    /**
     * (Opsional) Menghapus data. 
     * Jika Anda tidak ingin ada hapus beneran, boleh dihilangkan.
     */
    public function delete()
    {
        $id = request('id');
        $r = MemberRewardModel::query()->whereIn('id', $id)->delete();

        // LogController::writeLog(...)

        return response()->json([
            'data' => $r
        ]);
    }
}
