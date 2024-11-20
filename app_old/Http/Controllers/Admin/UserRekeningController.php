<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CabangModel;
use App\Models\UserRekeningModel;
use Illuminate\Http\Request;

class UserRekeningController extends Controller
{
    public function index(){
        return view('admin.userrekening.table');
    }


    public function datasource(){
        $id = \request('id');
        return datatables(UserRekeningModel::query())->make(true);
    }

    public function delete(){
        $id = \request('id');
        $r = UserRekeningModel::query()->whereIn('id', $id)->delete();
        return response()->json([
            'data'=>$r
        ]);
    }
}
