<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelMemberModel extends Model
{
    use HasFactory;
    protected $table = 'level_member';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public const UPDATED_AT = null;
    public const CREATED_AT = null;

    public function flimit_transaction(){
        return number_format($this->limit_transaction, 0);
    }
}
