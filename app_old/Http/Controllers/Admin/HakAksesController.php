<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\AccessRightModel;
use App\Models\EventsModel;
use App\Models\ProductModel;
use App\Models\RoleAccessRightModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;

class HakAksesController extends Controller
{
    public function index(){
        return view('admin.hakakses.table');
    }

    public function datasource(){

        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_HAK_AKSES)){
            return [];
        }

        return datatables(RoleModel::query())->make(true);
    }

    public function delete(){

        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_HAK_AKSES)){
            return ;
        }

        $id = \request('id');
        $myroleid = session('admin')->role_id;
        $r = RoleModel::query()->where('id','<>', $myroleid)->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_HAK_AKSES, 'Hapus  Peran  ', $id);
        return response()->json([
            'data'=>$r
        ]);
    }

    public function delete_role_access_right($roleid){

        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_HAK_AKSES)){
            return ;
        }


        $r = RoleModel::find($roleid);
        if($r == null)return abort(404);

        $myroleid = session('admin')->role_id;
        $id = \request('id');
        $r = RoleAccessRightModel::query()->where('role_id','<>', $myroleid)->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_HAK_AKSES, 'Hapus role access right  ', $id);
        return response()->json([
            'data'=>$r
        ]);
    }

    public function roleAccessRight($roleid){
        $role = RoleModel::find($roleid );
        if($role == null)return abort(404);

        return view('admin.hakakses.role_access.table', [
            'role'=>$role
        ]);
    }

    public function datasource_roleacessright($roleid){

        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_HAK_AKSES)){
            return ;
        }

        $v = RoleAccessRightModel::view()->where('role_id', $roleid)
                ->select(['id', 'role', 'access_rights', 'module', 'grant']);
        return datatables($v)->toJson();
    }


    public function select2role(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(RoleModel::query(), ['name'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id, 'text'=>$k->name ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }

    public function select2_roleaccessright(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(AccessRightModel::query(), ['name','module'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id, 'text'=>$k->name . ' - '.$k->module ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }
}
