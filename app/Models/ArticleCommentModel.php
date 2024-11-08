<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCommentModel extends Model
{
    use HasFactory;
    protected $table = 'article_comments';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}
