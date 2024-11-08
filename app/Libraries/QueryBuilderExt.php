<?php

namespace App\Libraries;

use Illuminate\Database\Eloquent\Builder;

class QueryBuilderExt
{
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
    public static function whereFilter($builder, Array $columns, $keyword = '', $isPGSQL = false) {
        $ar = (new QueryBuilderExt())->makeSplitForLikeQuery($keyword ?? '');
        $r = $builder;
        foreach ($ar as $k){
            $r->where(function($r) use ($k, $columns, $isPGSQL){
                if($k == '')return;
                foreach($columns as $ff){
                    $r->where($ff, ($isPGSQL ? 'ILIKE' : 'LIKE'), $k, 'OR');
                }
            });
        }
        return $r;
    }

    static public function checkMakeDir($dir){
        if(!file_exists($dir)){
            mkdir($dir, recursive: true);
        }
    }
}
