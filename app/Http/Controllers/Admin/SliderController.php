<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use App\Models\SliderModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{

    public function index(){

        return view('admin.sliders.table');
    }

    public function image($id){
        $pi = SliderModel::find($id);
        if($pi == null) abort(404);

        $fn =  $pi->image_path;
        if(!Storage::exists($fn) && $fn != ''){
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type'=>'image/png'
        ]);
    }

    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_SLIDER)){
            return [];
        }

        return datatables(SliderModel::query()->orderBy('order','asc'))
                    ->addColumn('urlfoto', function($v){
                        $f = url('/admin/slider/image/'.$v['id'].'.png');
                        if(Storage::exists($v['image_path']) && $v['image_path'] != ''){
                            return $f;
                        }
                        return '';
                    })
                    ->editColumn('created_at', function($v){
                        if($v['created_at'] === '' || $v['created_at'] === null)return null;
                        return Carbon::parse($v['created_at'])->diffForHumans();
                    })
                    ->editColumn('updated_at', function($v){
                        if($v['updated_at'] === '' || $v['updated_at'] === null)return null;
                        return Carbon::parse($v['updated_at'])->diffForHumans();
                    })
                    ->toJson();
    }

    private function hapusimage(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_SLIDER)){
            return ;
        }


        $id = \request('id');
        $imgs = SliderModel::query()->whereIn('id', $id)->get();
        foreach($imgs as $img){
            try {
                Storage::delete($img->image_path);
            }catch (\Exception $e){}
        }
        LogController::writeLog(ValidatedPermission::UBAH_DATA_SLIDER, 'Merubah slider ', $id);
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_SLIDER)){
            return ;
        }


        $id = \request('id');
        $this->hapusimage();
        $r = SliderModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_SLIDER, 'Menghapus slider', $id);
        return response()->json([
            'data'=>$r
        ]);
    }
}
