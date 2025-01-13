<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberSpentModel extends Model
{
    use HasFactory;
    protected $table = 'member_spent';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public const UPDATED_AT = null;
    public const CREATED_AT = null;

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(LevelMemberModel::class, 'level', 'id');
    }
}
