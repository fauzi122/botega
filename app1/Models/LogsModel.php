<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class LogsModel extends Model
{
    use HasFactory;
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function view(){
        return LogsModel::from(function (Builder $builder){
            return $builder->from('logs as l')
                           ->leftJoin('users as u', 'u.id', '=', 'l.user_id')
                           ->leftJoin('users as adm', 'adm.id', '=', 'l.admin_id')
                           ->select(['l.*', 'u.first_name', 'u.last_name',  'u.foto_path', 'u.id_no', 'u.user_type', 'adm.first_name as admin_first_name',
                                'adm.last_name as admin_last_name'
                               ]);
        }, 'logs');
    }


}
