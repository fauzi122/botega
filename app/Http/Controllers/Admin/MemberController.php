<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\LogsModel;
use App\Models\KategoriMemberModel;
use App\Models\ProductModel;
use App\Models\RequestUpdateModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function getPoints($userId)
    {
        $user = UserModel::find($userId);
        return response()->json(['points' => $user->points ?? 0]);
    }

    public function listapproval()
    {
        LogsModel::where('actions', 'Update Profile')->update(['status' => 1]);
        return view('admin.approval.table');
    }

    public function datasource_submit()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)) {
            return [];
        }

        return datatables(RequestUpdateModel::view()->where('status', 'Submited')->select([
            'id',
            'status',
            'created_at',
            'first_name',
            'last_name',
            'level_name',
            'reason_user',
            'reason_admin',
        ]))->addColumn('member', function ($row) {
            return $row['first_name'] . ' ' . $row['last_name'];
        })->editColumn('created_at', function ($row) {
            return Carbon::parse($row['created_at'])->diffForHumans();
        })->toJson();
    }

    public function datasource_approval()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)) {
            return [];
        }

        return datatables(RequestUpdateModel::view()->where('status', 'Approved')->select([
            'id',
            'status',
            'created_at',
            'first_name',
            'last_name',
            'level_name',
            'reason_user',
            'reason_admin'
        ]))->addColumn('member', function ($row) {
            return $row['first_name'] . ' ' . $row['last_name'];
        })->editColumn('created_at', function ($row) {
            return Carbon::parse($row['created_at'])->diffForHumans();
        })->toJson();
    }

    public function datasource_reject()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)) {
            return [];
        }

        return datatables(RequestUpdateModel::view()->where('status', 'Rejected')->select([
            'id',
            'status',
            'created_at',
            'first_name',
            'last_name',
            'level_name',
            'reason_user',
            'reason_admin'
        ]))->addColumn('member', function ($row) {
            return $row['first_name'] . ' ' . $row['last_name'];
        })->editColumn('created_at', function ($row) {
            return Carbon::parse($row['created_at'])->diffForHumans();
        })->toJson();
    }

    public function index()
    {
        $categories = KategoriMemberModel::all();
        return view('admin.member.table', compact('categories'));
    }

    public function info($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)) {
            return [];
        }

        $m = UserModel::view()->where('user_type', 'member')
            ->select(['id', 'id_no', 'first_name', 'last_name', 'user_type', 'level_member_id', 'level_name'])->find($id);
        if ($m == null) abort(404);
        return response()->json($m);
    }

    public function select2()
    {
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(
            UserModel::query()->where('user_type', 'member'),
            ['first_name', 'last_name', 'id_no'],
            $q
        )->paginate(10);
        $ret[] = ['id' => '', 'text' => '--'];
        foreach ($r as $k) {
            $ret[] = ['id' => $k->id, 'text' => $k->first_name . ' ' . $k->last_name . ' (' . $k->id_no . ')'];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }


    public function select2profesional()
    {
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(
            UserModel::view()
                ->whereRaw('(kategori=? OR reward_type IN(?,?) )',  ['MEMBER PRO', 1, 3])
                ->where('user_type', 'member'),
            ['first_name', 'last_name', 'id_no'],
            $q
        )->paginate(10);
        $ret[] = ['id' => '', 'text' => '--'];
        foreach ($r as $k) {
            $ret[] = ['id' => $k->id, 'text' => $k->first_name . ' ' . $k->last_name . ' (' . $k->id_no . ')'];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }
    public function select2profesional2()
    {
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(
            UserModel::view()
                // ->whereRaw('(kategori=? OR reward_type IN(?,?) )',  ['MEMBER PRO', 1, 3])
                ->where('user_type', 'member'),
            ['first_name', 'last_name', 'id_no'],
            $q
        )->paginate(10);
        $ret[] = ['id' => '', 'text' => '--'];
        foreach ($r as $k) {
            $ret[] = ['id' => $k->id, 'text' => $k->first_name . ' ' . $k->last_name . ' (' . $k->id_no . ')'];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }


    public function foto($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)) {
            return abort(403);
        }

        $u = UserModel::query()->find($id);
        if ($u == null) return abort(404);

        $f = Storage::get('photo/' . $id . '.png');
        if ($f != null) {
            return response($f, headers: [
                'Content-type' => 'image/png'
            ]);
        }
        return response(file_get_contents(public_path('assets/images/nofotoprofile.png')), headers: [
            'Content-type' => 'image/png'
        ]);
    }

    public function datasource(Request $request)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)) {
            return [];
        }

        $query = UserModel::view()->where('user_type', 'member');

        // Filter berdasarkan tipe
        if ($request->has('type')) {
            if ($request->type == 'profesional') {
                $query->whereIn('reward_type', [1, 3]); // Profesional
            } elseif ($request->type == 'member') {
                $query->where('reward_type', 2); // Member
            }
        }
        // Filter berdasarkan kategori
        if ($request->has('category') && !empty($request->category)) {
            $query->where('kategori_id', $request->category);
        }
        return datatables($query)->make(true);
    }

    public function delete()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_MEMBER)) {
            return [];
        }
        $id = \request('id');
        $r = UserModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_MEMBER, 'Hapus data member', $id);

        return response()->json([
            'data' => $r
        ]);
    }

    public function delete_approval()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_MEMBER)) {
            return [];
        }

        $id = \request('id');
        $r = RequestUpdateModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_MEMBER, 'Menghapus data pengajuan perubahan data member', $id);
        return response()->json([
            'data' => $r
        ]);
    }
    public function syncMemberById($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)) {
            return response()->json(['message' => 'Tidak memiliki izin untuk sinkronisasi data member.'], 403);
        }

        // Menjalankan job untuk sinkronisasi data member berdasarkan ID
        \App\Jobs\SyncMemberJob::dispatch('sync')->syncMemberById($id);

        return response()->json(['message' => 'Sinkronisasi job telah dijalankan'], 200);
    }
}
