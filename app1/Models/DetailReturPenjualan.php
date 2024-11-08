<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReturPenjualan extends Model
{
    use HasFactory;
    protected $table = 'detail_retur_penjualan';
    protected $fillable = ['*'];
    protected $guarded = [];
}
