<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ArticleModel;
use Illuminate\Http\Request;

class Article extends Controller
{
    //
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
}
