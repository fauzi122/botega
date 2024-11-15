<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Library\ValidatedPermission;
use App\Models\ProductCategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use App\Models\PromoModel;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use const Grpc\STATUS_OUT_OF_RANGE;

class ProdukController extends Controller
{
    public function index(){
        return view('admin.produk.table');
    }

    public function select2(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(ProductModel::view(), ['kode', 'name', 'category', 'descriptions'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->id, 'text'=>$k->kode . ' - '.$k->name ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }

    public function select2merk(){
        $q = \request('q');
        $r = QueryBuilderExt::whereFilter(ProductModel::merk(), ['merk'],
            $q
        )->paginate(10);
        $ret[] = ['id'=>'', 'text'=>'--'];
        foreach($r as $k){
            $ret[] = ['id'=>$k->merk, 'text'=>$k->merk ];
        }
        return response()->json([
            'items' => $ret,
            'total_items' => count($ret)
        ]);
    }

    public function image($id){
        $p = ProductImageModel::where('product_id', $id)
                ->orderBy('is_primary','desc')->first();
        $f = Storage::get('no-foto.png');
        $code = 404;
        if($p != null){
            try {
                $f = Storage::get($p->path_file);
                $code = 200;
            }catch (\Exception $e){}
        }


        return \response($f, $code, [
            'Content-type' => 'image/png'
        ]);
    }

    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PRODUK)){
            return [];
        }

        $id = \request('id');
        return datatables(ProductModel::view())->make(true);
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_PRODUK)){
            return ;
        }

        $id = \request('id');
        $this->hapusimage();
        $r = ProductModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_PRODUK, 'Hapus data produk ', $id);
        return response()->json([
            'data'=>$r
        ]);
    }

    private function hapusimage(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_PRODUK)){
            return ;
        }

        $id = \request('id');
        $imgs = ProductImageModel::query()->whereIn('product_id', $id)->get();
        foreach($imgs as $img){
            try {
                Storage::delete($img->path_file);
            }catch (\Exception $e){}
        }
        ProductImageModel::query()->whereIn('product_id', $id)->delete();
        LogController::writeLog(ValidatedPermission::UBAH_DATA_PRODUK, 'Hapus gambar produk ', $id);
    }
}
