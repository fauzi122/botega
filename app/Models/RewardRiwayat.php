<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRiwayat extends Model
{
    use HasFactory;
    protected $table = 'reward_riwayat';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}
