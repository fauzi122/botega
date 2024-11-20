<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesHistoryModel extends Model
{
    use HasFactory;
    protected $table = 'proses_history';
    public const CREATED_AT = null;
    public const UPDATED_AT = null;
    protected $fillable = ['*'];
}
