<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KatalogProdukModel;
use App\Models\ProductModel;
use App\Models\PromoModel;
use App\Models\SliderModel;
use App\Models\YoutubeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Home extends Controller
{
    //
    public function index(){
        $promo = DB::table('promo as a')
            ->select('a.*', 'b.kategori', 'c.name')
            ->leftJoin('level_member as b', 'a.level_member_id', '=', 'b.id')
            ->leftJoin('products as c', 'a.product_id', '=', 'c.id')
            ->addSelect(DB::raw('(SELECT path_file FROM product_images WHERE product_id = a.product_id ORDER BY is_primary LIMIT 1) as path_file'))
            ->where('a.level_member_id', '=', session('user')->level_member_id)
            ->limit(3)
            ->havingRaw('path_file IS NOT NULL')
            ->get();



        $data = [
            'title'=> 'Home',
            'home'=>'',
            'promo'=>$promo


        ];
        return view('frontend.home.home',$data);
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



}
