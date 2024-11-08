<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGaleryModel extends Model
{
    use HasFactory;
    protected $table = 'event_galeries';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}
