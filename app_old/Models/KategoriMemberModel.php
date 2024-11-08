<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMemberModel extends Model
{
    use HasFactory;

    protected $table = 'kategori_member';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
}
