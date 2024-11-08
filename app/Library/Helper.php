<?php
namespace App\Library;


use Illuminate\Database\Query\Builder;
use PHPUnit\TextUI\Help;

class Helper{

    private function makeSplitForLikeQuery($keyword){
        $t = [];
        foreach(explode(' ', $keyword) as $k){
            if(trim($k) != ''){
                $t[] = "%$k%";
            }
        }
        return $t;
    }

    /**
     * @param $builder
     * @param array $columns
     * @param string $keyword
     * @return Builder
     */
    public static function whereFilter($builder, Array $columns, $keyword = '') {
        $ar = (new Helper())->makeSplitForLikeQuery($keyword ?? '');
        $r = $builder;
        foreach ($ar as $k){
            $r->where(function($r) use ($k, $columns){
                if($k == '')return;
                foreach($columns as $ff){
                    $r->where($ff, 'LIKE', $k, 'OR');
                }
            });
        }
        return $r;
    }

    static public function setSessionAll($sessname, $data){
        $dirname = __DIR__ .'/cache/';
        Helper::checkMakeDir($dirname);
        return @file_put_contents($dirname . $sessname, json_encode($data)) != false;
    }

    static function getSession($sessname){
        $dirname = __DIR__ .'/cache/';
        Helper::checkMakeDir($dirname);
        $ss      = @file_get_contents($dirname . $sessname);
        return json_decode($ss, true) ?? [];
    }


    static public function checkMakeDir($dir){
        if(!file_exists($dir)){
            mkdir($dir, recursive: true);
        }
    }

    static public function sort($array, $callback){
        if(!is_array($array))return $array;
        $len = count($array);
        if($len <= 1)return $array;

        $buff = array_merge($array);

        for($i=0; $i<$len-1; $i++){
            for($j=$i+1; $j < $len ; $j++){
                $compare = $callback($buff[$i], $buff[$j]);
                if($compare == true){
                    $tmp = $buff[$i];
                    $buff[$i] = $array[$j];
                    $buff[$j] = $tmp;
                }
            }
        }
        return $buff;
    }

}
