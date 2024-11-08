<?php

namespace App\Livewire\Frontend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Produk extends Component
{
    public function render()
    {

        $produk = DB::table('products as a')
            ->select('a.*', 'b.path_file', 'b.is_primary')
            ->leftJoin('product_images as b', 'a.id', '=', 'b.product_id')
            ->limit(4)
            ->orderBy('a.id','desc')
            ->get();
        $data = [
            'produk'=>$produk
        ];
        return view('livewire.frontend.produk',$data);
    }
}
