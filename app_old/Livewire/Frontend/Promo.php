<?php

namespace App\Livewire\Frontend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Promo extends Component
{
    public function render()
    {
        $promo = DB::table('promo as a')
            ->select('a.*', 'b.kategori', 'c.name')
            ->leftJoin('level_member as b', 'a.level_member_id', '=', 'b.id')
            ->leftJoin('products as c', 'a.product_id', '=', 'c.id')
            ->addSelect(DB::raw('(SELECT path_file FROM product_images WHERE product_id = a.product_id ORDER BY is_primary LIMIT 1) as path_file'))
            ->where('a.level_member_id', '=', session('user')->level_member_id)
            ->limit(3)
            ->havingRaw('path_file IS NOT NULL')
            ->get();

        return view('livewire.frontend.promo', [
            'promo' => $promo
        ]);

    }
}
