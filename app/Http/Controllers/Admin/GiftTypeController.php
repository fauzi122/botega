<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\GiftTypeModel;
use App\Models\LevelMemberModel;
use Illuminate\Http\Request;

class GiftTypeController extends Controller
{

    public function index(){
        return view('admin.gifttype.table');
    }



    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_JENIS_HADIAH)){
            return [];
        }

        $id = \request('id');
        return datatables(GiftTypeModel::query())->editColumn('level_member_id', function ($gt) {
            $lvl = LevelMemberModel::find($gt->level_member_id);

            return $lvl->level_name;
        })->toJson();
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_JENIS_HADIAH)){
            return ;
        }


        $id = \request('id');
        $r = GiftTypeModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_JENIS_HADIAH, 'Hapus Gift Type ', $id);
        return response()->json([
            'data'=>$r
        ]);
    }
}
