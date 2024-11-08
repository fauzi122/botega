<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeModel extends Model
{
    use HasFactory;
    protected $table = 'youtube_videos';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}
