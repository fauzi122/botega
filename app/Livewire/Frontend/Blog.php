<?php

namespace App\Livewire\Frontend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Blog extends Component
{
    public function render()
    {
        $article  = DB::table('articles as a')
            ->select('a.*', 'b.category', 'c.first_name', 'c.last_name')
            ->leftJoin('article_categories as b', 'a.article_category_id', '=', 'b.id')
            ->leftJoin('users as c', 'a.pengelola_user_id', '=', 'c.id')
            ->orderBy('a.id', 'desc')
            ->limit(3)
            ->get();
        $data = [
            'article'=>$article
        ];
        return view('livewire.frontend.blog',$data);
    }
}
