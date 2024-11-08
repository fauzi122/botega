<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\ArticleModel;
use App\Models\CabangModel;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index(){
        return view('admin.cabang.table');
    }


    public function datasource(){
        if(!ValidatedPermission::authorize('Cabang.Read')){
            return [];
        }

        $id = \request('id');
        return datatables(CabangModel::query())->make(true);
    }

    public function delete(){
        if(!ValidatedPermission::authorize('Cabang.Delete')){
            return;
        }

        $id = \request('id');
        $r = CabangModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_CABANG, 'Menghapus data Cabang', $id);

        return response()->json([
            'data'=>$r
        ]);
    }
}
