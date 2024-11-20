<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB as FacadesDB;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function view(){
        return ProductModel::query()->from(function(Builder $bp){
               return $bp->from('products as p')
                         ->leftJoin('product_categories as pc', 'pc.id','=','p.category_id')
                         ->leftJoin('users as u','u.id','=','p.users_id')
                         ->selectRaw('p.*, pc.category, u.first_name');
        }, 'products');
    }


    public static function merk(){
        return ProductModel::query()->from(function(Builder $bp){
               return $bp->from('products as p')
                         ->select([
                            \DB::raw('SUBSTRING_INDEX(p.name, " ", 1) as merk')
                         ])->groupBy('merk');
        });
    }
}
