<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\ArticleModel;
use App\Models\ProductCategoryModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;

class ArtikelController extends Controller
{
    use WithFileUploads;

    public function index(){
        return view('admin.artikel.table');
    }

    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_ARTIKEL)){
            return [];
        }
        $id = \request('id');
        Carbon::setLocale('id');
        return datatables(ArticleModel::view()
                ->select(['id', 'published_at', 'judul', 'kode', 'product',
                    'first_name', 'last_name', 'created_at']) )
                ->editColumn('created_at', function($row){
                    if($row['created_at'] == '')return '';
                    return Carbon::parse($row['created_at'])->translatedFormat('d M Y, H:i');
                })
                ->editColumn('published_at', function($row){
                    if($row['published_at'] == '')return '';
                    return Carbon::parse($row['published_at'])->translatedFormat('l, d F Y');
                })
                ->editColumn('expired_at', function($row){
                    if($row['expired_at'] == '')return '';
                    return Carbon::parse($row['expired_at'])->translatedFormat('l, d F Y');
                })
                ->toJson();
    }

    public function image($id){
        $r = ArticleModel::find($id);
        if($r === null)return abort(404);
        if(!\Storage::exists($r->path_images)){
            return abort(404);
        }
        return response( \Storage::get($r->path_images), headers: [
            'Content-type' => 'image/png'
        ] );
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_ARTIKEL)){
            return ;
        }

        $id = \request('id');
        $r = ArticleModel::query()->whereIn('id', $id)->delete();
        return response()->json([
            'data'=>$r
        ]);
    }
}
