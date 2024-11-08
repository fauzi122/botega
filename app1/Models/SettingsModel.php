<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $fillable = ['*'];
    protected $guarded = [];

    public static function set($name, $value){
        return (new SettingsModel())->newQuery()->updateOrInsert(["keyname"=>$name],[
            "keyvalue"=>$value
        ]);
    }

    public static function get($name, $default = ''){
        $r = (new SettingsModel())->newQuery()->where('keyname',$name)->first();
        return $r?->keyvalue ?? $default;
    }
}
