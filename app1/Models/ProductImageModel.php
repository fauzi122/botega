<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImageModel extends Model
{
    use HasFactory;
    protected $table = 'product_images';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public function getCreatedAtAttribute($v){
        return Carbon::parse($v)->diffForHumans();
    }
}
