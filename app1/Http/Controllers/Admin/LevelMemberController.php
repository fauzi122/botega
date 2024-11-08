<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LevelMemberController extends Controller
{
    public function index(){
        return view('admin.levelmember.table');
    }

    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_LEVEL_MEMBER)){
            return [];
        }

        return DataTables::of(LevelMemberModel::query())->make(true);
    }

    public function store(){
        $r = \request()->validate([
            'level_name' => 'required'
        ]);

    }


    public function delete()
    {
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_LEVEL_MEMBER)){
            return [];
        }

        $id = \request('id');
        $r = LevelMemberModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_LEVEL_MEMBER, 'Hapus data level member', $id);

        return response()->json([
            'data' => $r
        ]);
    }
}
