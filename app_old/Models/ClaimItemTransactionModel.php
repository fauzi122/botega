<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ClaimItemTransactionModel extends Model
{
    use HasFactory;
    protected $table = 'claim_item_transaction';
    protected $fillable = ['*'];
    protected $guarded = [];

}
