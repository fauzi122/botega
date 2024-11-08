<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\MemberPointModel;
use App\Models\RewardModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index(){
        return view('admin.reward.table');
    }


    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_REWARD)){
            return [];
        }
        $id = \request('id');
        return datatables(RewardModel::query() )
                ->editColumn('expired_at', function($r){
                    if($r['expired_at'] == null || $r['expired_at'] == ''){
                        return '';
                    }
                    return Carbon::parse($r['expired_at'])->translatedFormat('l, d M Y');
                })
                ->editColumn('path_image', function($e){
                    if($e['path_image'] == null || $e['path_image'] == ''){return '';}
                    return url('admin/reward/pic/'.$e['id'].'.png');
                })
                ->toJson();
    }

    public function getPic($id){
        $rw = RewardModel::query()->find($id);
        if($rw == null){
            return response(
                file_get_contents('assets/images/bottega-brown.png'), 200,[
                    'Content-Type' => 'image/png'
                ] );
        }
        return response(
            \Storage::get($rw->path_image), 200, [
                'Content-Type' => 'image/png'
            ]);
    }


    public function select2(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(RewardModel::query(), ['first_name', 'last_name', 'id_no'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id,
                      'text'=>$k->code . ' ' . $k->name . ' ('.$k->point.')',
                      'point' => $k->point
                ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }

    public function delete()
    {
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_REWARD)){
            return ;
        }

        $id = \request('id');
        $r = RewardModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_REWARD, 'Hapus data Reward', $id);
        return response()->json([
            'data' => $r
        ]);
    }
}
