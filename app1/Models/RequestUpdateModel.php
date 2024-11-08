<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class RequestUpdateModel extends Model
{
    use HasFactory;
    protected $table = 'request_updates';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function view(){
        return RequestUpdateModel::query()->from(function(Builder $b){
            return $b->from('request_updates as a')
                     ->leftJoin('users as b', 'b.id', '=', 'a.user_id')
                     ->leftJoin('level_member as c', 'c.id', '=', 'b.level_member_id')
                     ->selectRaw('a.*, b.first_name, b.last_name, c.level_name, c.level');
        }, 'request_updates');
    }
}
