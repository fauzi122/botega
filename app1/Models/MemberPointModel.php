<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class MemberPointModel extends Model
{
    use HasFactory;
    protected $table = 'member_points';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];


}
