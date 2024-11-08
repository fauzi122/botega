<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ArticleModel extends Model
{
    use HasFactory;
    protected $table = 'articles';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function view(){
        return ArticleModel::from(function(Builder $b){
            return $b->from('articles as a')
                     ->leftJoin('article_categories as b', 'b.id', '=', 'a.article_category_id')
                     ->leftJoin('products as p', 'p.id', '=', 'a.product_id')
                     ->leftJoin('users as u', 'u.id','=','a.pengelola_user_id')
                    ->select(['a.*', 'b.category', 'p.kode', 'p.name as product','u.first_name', 'u.last_name']);
        }, 'articles');
    }

    public function getPubslihedAs(){
        if($this?->published_at == null || $this?->published_at == ''){return  '';}
        return Carbon::parse($this->published_at)->format('Y-m-d');
    }


    public function getExpiredtat(){
        if($this?->expired_at == null || $this?->expired_at == ''){return  '';}
        return Carbon::parse($this->expired_at)->format('Y-m-d');
    }
}
