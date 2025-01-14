<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class MemberRewardModel extends Model
{
    use HasFactory;
    protected $table = 'member_rewards';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public function getCreatedAtAttribute($v)
    {
        if ($v == null || $v == '') return '';
        return Carbon::parse($v, 'Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public function getApprovedAtAttribute($v)
    {
        if ($v == null || $v == '') return '';
        return Carbon::parse($v, 'Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public static function view()
    {
        return MemberRewardModel::from(function (Builder $b) {
            return $b->from('member_rewards as m')
                ->leftJoin('users as u', 'u.id', '=', 'm.user_id')
                ->leftJoin('rewards as r', 'r.id', '=', 'm.reward_id')
                ->leftJoin('users as pg', 'pg.id', '=', 'm.pengelola_user_id')
                ->select([
                    'm.*',
                    'u.id_no',
                    'u.first_name',
                    'u.last_name',
                    'u.points',
                    'u.total_spent',
                    'r.code as reward_code',
                    'r.point as reward_point',
                    'r.name as reward',
                    'pg.first_name as pengelola'
                ]);
        }, 'member_rewards');
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
