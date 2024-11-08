<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\LevelMemberModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;
use App\Models\PromoModel;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
    public function index(){
        return view('admin.kategoriproduk.table');
    }


    public function select2(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(ProductCategoryModel::query(), ['category', 'descriptions'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id, 'text'=>$k->category ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }


    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_KATEGORI_PRODUK)){
            return [];
        }

        $id = \request('id');
        return datatables(ProductCategoryModel::query())->make(true);
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_KATEGORI_PRODUK)){
            return ;
        }

        $id = \request('id');
        $r = ProductCategoryModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_KATEGORI_PRODUK, 'Hapus Kategori Produk ', $id);
        return response()->json([
            'data'=>$r
        ]);
    }
}
