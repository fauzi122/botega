<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberSpentModel;
use App\Models\UserModel;
use App\Models\LevelMemberModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MemberSpentController extends Controller
{
    /**
     * Tampilkan halaman utama.
     */
    public function index()
    {
        return view('admin.member-spent.index');
    }

    /**
     * Mendapatkan data untuk DataTables.
     */
    public function getData(Request $request)
    {
        $query = MemberSpentModel::query()
            ->join('users', 'users.id', '=', 'member_spent.user_id')
            ->join('level_member', 'level_member.id', '=', 'member_spent.level')
            ->select(
                'member_spent.id',
                'users.id_no',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"), // Gabungkan first_name dan last_name
                'member_spent.tahun',
                'member_spent.total_spent',
                'level_member.level_name'
            );

        return DataTables::of($query)
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(users.first_name, ' ', users.last_name) LIKE ?", ["%{$keyword}%"]);
            })
            ->addIndexColumn() // Tambahkan nomor urut otomatis
            ->make(true);
    }
}
