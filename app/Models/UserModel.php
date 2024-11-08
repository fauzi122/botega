<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class UserModel extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';

    protected $hidden = [
        'token_reset', 'sandi'
    ];

    protected $casts = [
        'code_verify_email_expire' => 'datetime',
        'code_verify_nohp_expire' => 'datetime',
        'date_verify_email' => 'datetime',
        'date_verify_nohp' => 'datetime',
        'sandi' => 'hashed',
    ];

    public static function view(){
        return UserModel::query()->from(function(Builder $b){
                return $b->from('users as a')
                         ->leftJoin('level_member as b', 'a.level_member_id','=','b.id')
                        ->leftJoin('kategori_member as km', 'km.id', '=', 'a.kategori_id')
                         ->leftJoin('roles as r', 'a.role_id','=','r.id')
                         ->select(['a.*', 'b.level_name', 'b.level', 'r.name as role', 'km.name as kategori']);
        }, 'users');
    }
}
