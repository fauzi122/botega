<?php

namespace App\Livewire\Frontend;

use App\Models\KatalogProdukModel;
use Livewire\Component;

class Catalog extends Component
{
    public function render()
    {
        $member = session('user')->level_member_id;
//        var_dump($member);die();
        $katalog = KatalogProdukModel::query()->where('lvl_member_id', $member)->limit(3)->get();
        $data = [
            'katalog'=>$katalog
        ];
        if ($katalog->isNotEmpty()) {
            return view('livewire.frontend.catalog',$data);
        } else {
            // Jika $promo kosong atau null, tampilkan komponen dengan pesan yang sesuai
            return "<div></div>";
        }

    }
}
