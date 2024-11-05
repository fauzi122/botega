<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\ArticleCategoryModel;
use App\Models\LogsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(){
        LogsModel::where('status',0)->update(['status'=>1]);

        return view('admin.logs.table');
    }

    public function datasource(){
        $id = \request('id');
        return datatables(LogsModel::view())
                ->editColumn('created_at', function($e){
                    return Carbon::parse($e['created_at'])->translatedFormat('l, d M Y. H:i');
                })->editColumn('payload', function($e){
                    $js = json_decode($e['payload'],true);

                    return $js['description'] ?? '';
                })
                ->toJson();
    }

    public function delete(){
        $id = \request('id');
        $r = LogsModel::query()->whereIn('id', $id)->delete();
        return response()->json([
            'data'=>$r
        ]);
    }

    public static function writeLog($actionName, $deskripsi, $data=[], $status = 1, $userid=null){
        $payload = [
            'description' => $deskripsi,
            'ip' => \request()->ips(),
            'data' => $data
        ];

        LogsModel::query()->insert([
            'actions' => $actionName,
            'payload' => json_encode($payload),
            'user_id' => $userid,
            'admin_id' => session('admin')?->id,
            'status' => $status,
            'created_at' => Carbon::now()
        ]);
    }

}
