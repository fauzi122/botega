<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\AccessRightModel;
use App\Models\ArticleCategoryModel;
use App\Models\RewardModel;
use Illuminate\Http\Request;

class KategoriArtikelController extends Controller
{
    public function index(){
        return view('admin.kategoriartikel.table');
    }

    public function select2(){

        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(ArticleCategoryModel::query()->where('publish',1), ['category' ],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id,
                'text'=>$k->category,
            ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);

    }

    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_KATEGORI_ARTIKEL)){
            return ;
        }

        $id = \request('id');
        return datatables(ArticleCategoryModel::query())->make(true);
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_KATEGORI_ARTIKEL)){
            return ;
        }

        $id = \request('id');
        $r = ArticleCategoryModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_KATEGORI_ARTIKEL, 'Hapus Kategori Artikel', $id);
        return response()->json([
            'data'=>$r
        ]);
    }
}
