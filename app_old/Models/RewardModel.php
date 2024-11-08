<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardModel extends Model
{
    use HasFactory;
    protected $table = 'rewards';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}
