<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftTypeModel extends Model
{
    use HasFactory;
    protected $table = 'gift_types';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}