<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeNumberDP extends Model
{
    use HasFactory;
    protected $table = 'fee_dp';
    protected $primaryKey = 'id';
    // protected $fillable = ['*'];
    protected $guarded = [];
}
