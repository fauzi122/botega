<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\PromoModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Services\DataTable;

class PromoController extends Controller
{
    public function index(){
        return view('admin.promo.table');
    }

    public function detail($id){
        $r = LevelMemberModel::query()->find($id);
        return view('admin.promo.detailtable', [
            'lvl' => $r
        ]);
    }

    public function select2(){
        $q = \request('q');
        $lvlmemberid = (int)\request('level_member_id');

        $r = QueryBuilderExt::whereFilter(
            PromoModel::productWithPromo()->where('level_member_id', $lvlmemberid),
            ['kode', 'name', 'category'],
            $q)->paginate(10);
//        $ret[] =  ['id'=>'', 'text'=>'--'];
        $ret = [];
        foreach($r as $k){
            $harga = number_format($k->price_promo);
            $ret[] = ['id'=>$k->id, 'text'=>"<b>{$k->kode} - {$k->name}</b>",
                        'name'=>$k->name,
                        'category'=>$k->category,
                        'harga'=>$harga
            ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }

    public function infoProduk($id, $lmi){
        $r = PromoModel::productWithPromo()->where('id',$id)
                        ->where('level_member_id', $lmi)->first([
            'kode', 'name', 'category', 'price_promo'
        ]);
        return response()->json($r);
    }


    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PROMO)){
            return ;
        }

        $id = \request('id');
        return datatables(LevelMemberModel::query())->make(true);
    }

    public function datasource_detail(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PROMO)){
            return ;
        }

        $id = \request('id');
        return datatables(PromoModel::view()->where('level_member_id',$id))
                ->editColumn('expired_at', function($e){
                    return Carbon::parse($e->expired_at)->translatedFormat('E, d F Y');
                })->make(true);
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_PROMO)){
            return ;
        }

        $id = \request('id');
        $r = PromoModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_PROMO, 'Hapus data Promo', $id);

        return response()->json([
            'data'=>$r
        ]);
    }
}
