<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatalogProdukModel extends Model
{
    use HasFactory;
    protected $table = 'katalog_produk';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';
}
