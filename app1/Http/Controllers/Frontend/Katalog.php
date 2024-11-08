<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KatalogProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Katalog extends Controller
{
    //
    public function image($id){
        $pi = KatalogProdukModel::find($id);
        if($pi == null) abort(404);

        $fn =  $pi->gambar_katalog;
        if(!Storage::exists($fn) && $fn != ''){
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type'=>'image/png'
        ]);
    }


    public function berkas($id){
        $pi = KatalogProdukModel::find($id);
        if($pi == null) abort(404);

        $fn =  $pi->file_katalog;
        if(!Storage::exists($fn) && $fn != ''){
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type'=>'application/pdf'
        ]);
    }
}
