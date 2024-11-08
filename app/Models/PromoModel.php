<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class PromoModel extends Model
{
    use HasFactory;
    protected $table = 'promo';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    protected $casts = [
        'expired_at' => 'datetime:'
    ];

    public function getExpiredAtAttribute($v){
        return Carbon::parse($v)->format('Y-m-d');
    }

    public function setExpiredAtAttribute($v){
        $this->attributes['expired_at'] = Carbon::createFromFormat('Y-m-d', $v);
    }

    public static function view(){
        return PromoModel::query()->from(function(Builder $b){
            return $b->from('promo as p')
                     ->leftJoin('level_member as lm', 'lm.id','=','p.level_member_id')
                     ->leftJoin('products as pd', 'pd.id','=','p.product_id')
                     ->leftJoin('product_categories as pc', 'pc.id','=','pd.category_id')
                     ->selectRaw('p.*, lm.level_name, pd.kode, pd.name as product, pd.price as price_base, pc.category ');

        }, 'promo');
    }
    public static function productWithPromo(){
        return ProductModel::from(function(Builder $b){
            return $b->from('products as p')
                     ->leftJoin('promo as po', 'po.product_id', '=', 'p.id')
                     ->leftJoin('product_categories as pc', 'pc.id', '=', 'p.category_id')
                     ->leftJoin('level_member as lm', 'lm.id', '=', 'po.level_member_id')
                     ->selectRaw('p.*, pc.category, po.price as price_promo, lm.id as level_member_id, lm.level_name');
        }, 'products');
    }

}
