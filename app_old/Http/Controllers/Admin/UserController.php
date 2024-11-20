<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\RewardModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('admin.pengguna.table');
    }

    public function datasource(){

        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PENGGUNA)){
            return [];
        }

        $id = \request('id');
        return datatables(
                    UserModel::view()->where('user_type', 'admin')
                )->toJson();
    }

    public function delete()
    {

        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_PENGGUNA)){
            return ;
        }

        $id = \request('id');
        $myid = session('admin')?->id;

        $r = UserModel::query()->where('id', '<>', $myid)->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_PENGGUNA, 'Hapus  Pengguna  ', $id);
        return response()->json([
            'data' => $r
        ]);
    }

    public function photo($id){
        $u = UserModel::where('user_type','admin')->find($id);
        if($u == null)return abort(404);
        $path = "pengguna/$id.png";
        $file = \Storage::exists($path);
        if(!$file){
            return abort(404);
        }

        LogController::writeLog(ValidatedPermission::UBAH_DATA_PENGGUNA, 'Hapus  foto Pengguna  ', $id);
        return response( \Storage::get($path), 200 , [
            'Content-Type' => 'image/png'
        ] );
    }
}
