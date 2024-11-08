<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class RoleAccessRightModel extends Model
{
    use HasFactory;
    protected $table = 'role_access_rights';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function view(){
        return RoleAccessRightModel::from(function(Builder $builder){
            return $builder->from('role_access_rights as ra')
                            ->leftJoin('roles as r', 'ra.role_id', '=','r.id')
                            ->leftJoin('access_rights as ar', 'ar.id','=','ra.access_right_id')
                            ->select(['ra.*', 'r.name as role', 'ar.name as access_rights', 'ar.module']);
        }, 'role_access_rights');
    }

    public static function buatHakAkses($roleID){
         $hakAkses = AccessRightModel::query()->get();
         $data = [];
         foreach ($hakAkses as $hak){
             $data[] = [
                 'role_id' => $roleID,
                 'access_right_id' => $hak->id,
                 'grant' => 0,
                 'created_at' => Carbon::now()
             ];
         }
         RoleAccessRightModel::query()->insert($data);
    }
}
