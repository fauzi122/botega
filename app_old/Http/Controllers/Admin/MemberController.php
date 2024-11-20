<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\LogsModel;
use App\Models\ProductModel;
use App\Models\RequestUpdateModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function listapproval(){
        LogsModel::where('actions','Update Profile')->update(['status'=>1]);
        return view('admin.approval.table');
    }

    public function datasource_submit(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)){
            return [];
        }

        return datatables(RequestUpdateModel::view()->where('status','Submited')->select([
            'id', 'status', 'created_at', 'first_name', 'last_name' ,'level_name', 'reason_user', 'reason_admin',
        ]))->addColumn('member', function ($row){
            return $row['first_name'] . ' ' . $row['last_name'];
        })->editColumn('created_at', function($row){
            return Carbon::parse($row['created_at'])->diffForHumans();
        }) ->toJson();
    }

    public function datasource_approval(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)){
            return [];
        }

        return datatables(RequestUpdateModel::view()->where('status','Approved')->select([
            'id', 'status', 'created_at', 'first_name', 'last_name' ,'level_name', 'reason_user', 'reason_admin'
        ]))->addColumn('member', function ($row){
            return $row['first_name'] . ' ' . $row['last_name'];
        })->editColumn('created_at', function($row){
            return Carbon::parse($row['created_at'])->diffForHumans();
        }) ->toJson();
    }

    public function datasource_reject(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)){
            return [];
        }

        return datatables(RequestUpdateModel::view()->where('status','Rejected')->select([
            'id', 'status', 'created_at', 'first_name', 'last_name' ,'level_name', 'reason_user', 'reason_admin'
        ]))->addColumn('member', function ($row){
            return $row['first_name'] . ' ' . $row['last_name'];
        })->editColumn('created_at', function($row){
            return Carbon::parse($row['created_at'])->diffForHumans();
        }) ->toJson();
    }

    public function index(){
        return view('admin.member.table');
    }

    public function info($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)){
            return [];
        }

        $m = UserModel::view()->where('user_type','member')
                ->select(['id','id_no','first_name', 'last_name', 'user_type', 'level_member_id', 'level_name'])->find($id);
        if($m == null)abort(404);
        return response()->json($m);
    }

    public function select2(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(UserModel::query()->where('user_type','member'), ['first_name', 'last_name', 'id_no'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id, 'text'=>$k->first_name . ' ' . $k->last_name . ' ('.$k->id_no.')' ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }


    public function select2profesional(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(UserModel::view()
                ->whereRaw('(kategori=? OR reward_type IN(?,?) )',  ['MEMBER PRO',1,3])
                ->where('user_type','member'), ['first_name', 'last_name', 'id_no'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id, 'text'=>$k->first_name . ' ' . $k->last_name . ' ('.$k->id_no.')' ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }


    public function foto($id){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)){
            return abort(403);
        }

        $u = UserModel::query()->find($id);
        if($u == null)return abort(404);

        $f = Storage::get('photo/'.$id.'.png');
        if($f != null){
            return response($f, headers: [
                'Content-type' => 'image/png'
            ]);
        }
        return response(file_get_contents( public_path( 'assets/images/nofotoprofile.png' ) ), headers: [
            'Content-type' => 'image/png'
        ]);
    }



    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_MEMBER)){
            return [];
        }

        $id = \request('id');
        return datatables(UserModel::view()->where('user_type','member') )->make(true);
    }

    public function delete()
    {
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_MEMBER)){
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
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_MEMBER)){
            return [];
        }

        $id = \request('id');
        $r = RequestUpdateModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_MEMBER, 'Menghapus data pengajuan perubahan data member', $id);
        return response()->json([
            'data' => $r
        ]);
    }
}
