<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WilayahModel extends Model
{
    use HasFactory;
    protected $table = 'wilayah';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}