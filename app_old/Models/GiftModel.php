<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class GiftModel extends Model
{
    use HasFactory;
    protected $table = 'gifts';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public function getSentAtAttribute($v){
        if($v == null)return null;
        return Carbon::parse($v)->format('Y-m-d');
    }

    public function getReceivedAtAttribute($v){
        if($v == null)return null;
        return Carbon::parse($v)->format('Y-m-d');
    }

    public static function view(){
        return GiftModel::from(function(Builder $b){
            return $b->from('gifts as g')
                     ->leftJoin('users as u', 'u.id', '=', 'g.user_id')
                     ->leftJoin('gift_types as gt', 'gt.id','=', 'g.gift_type_id')
                     ->leftJoin('users as pg', 'pg.id', '=', 'g.pengelola_user_id')
                     ->select(['g.*', 'u.id_no', 'u.first_name', 'u.last_name', 'gt.name as gift', 'pg.first_name as pengelola']);
        }, 'gifts');
    }
}
