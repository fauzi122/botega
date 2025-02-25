<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkModel extends Model
{
    use HasFactory;
    protected $table = 'marks';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}
