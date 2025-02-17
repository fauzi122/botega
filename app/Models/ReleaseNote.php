<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseNote extends Model
{
    use HasFactory;
    protected $table = 'release_note';
    protected $fillable = ['judul', 'kode', 'tipe', 'deskripsi'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($releaseNote) {
            $lastKode = ReleaseNote::latest('id')->first();
            $nextNumber = $lastKode ? substr($lastKode->kode, 4) + 1 : 1;
            $releaseNote->kode = 'BAI-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }
}
