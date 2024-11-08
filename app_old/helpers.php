<?php

    if( !function_exists('cseo')){
        function cseo($teks){
            return str_replace([' ','/', "\\", '?', '>', '<', '+'], '-', $teks);
        }
    }


    function formatNPWP($npwp)
    {
        if(strlen($npwp)<13)return $npwp;
        if( strpos($npwp, ".") > 0 || strpos($npwp, "-") > 2 ) return $npwp;

        // Pisahkan bagian-bagian NPWP
        $part1 = substr($npwp, 0, 2);      // 59
        $part2 = substr($npwp, 2, 3);      // 011
        $part3 = substr($npwp, 5, 3);      // 996
        $part4 = substr($npwp, 8, 1);      // 2
        $part5 = substr($npwp, 9, 3);      // 451
        $part6 = substr($npwp, 12);        // 000

        // Format NPWP sesuai dengan pola
        $formatted_npwp = $part1 . '.' . $part2 . '.' . $part3 . '.' . $part4 . '-' . $part5 . '.' . $part6;

        return $formatted_npwp;
    }


    function decodeBase64File($data, $prefix='data:image')
    {
        // Memeriksa apakah data dimulai dengan 'data:image'
        if (strpos($data, $prefix) !== 0) {
            return false; // Bukan data gambar base64
        }

        // Mengambil data base64 saja (menghapus bagian tipe file)
        $base64 = substr($data, strpos($data, ',') + 1);

        // Menguraikan data base64 menjadi string biner
        $decoded = base64_decode($base64);

        // Memeriksa apakah data berhasil di-decode
        if ($decoded === false) {
            return false; // Data bukan base64 yang valid
        }
        return $decoded;

    }

    function decodeBase64Image($data, $prefix='data:image')
    {
        $decoded = decodeBase64File($data, 'data:image');
        if($decoded == false)return false;

        // Memeriksa apakah data yang didecode adalah gambar
        $imageInfo = getimagesizefromstring($decoded);

        if ($imageInfo === false) {
            return false; // Data bukan gambar
        }

        return $decoded; // Data adalah gambar dalam format base64
    }

    function strip_cssjs($input)
    {
        $r = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
        return preg_replace("~\<style(.*)\>(.*)\<\/style\>~", '', $r);
    }

    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }

    if ( ! function_exists('php_unitbyte_to_truebyte'))
    {
        /**
         * @param $phpunitByte
         * @return int
         */
        function php_unitbyte_to_truebyte($phpunitByte)
        {
            $u = strtoupper( substr($phpunitByte, -1) );
            $s = (int) substr($phpunitByte, 0, -1);

            switch ($u){
                case 'Z': $s *= 1024;
                case 'T': $s *= 1024;
                case 'G': $s *= 1024;
                case 'M': $s *= 1024;
                case 'K': $s *= 1024;
            }
            return $s;
        }
    }

    if ( ! function_exists('ini_max_upload')) {

        /**
         * @return string
         */
        function ini_max_upload()
        {
            $pmz = ini_get('post_max_size');
            $muz = ini_get('upload_max_filesize');

            if (php_unitbyte_to_truebyte($pmz) < php_unitbyte_to_truebyte($muz)) {
                return $pmz;
            }
            return $muz;
        }
    }

    if( !function_exists('diff_array') ) {
        function diff_array($array1, $array2)
        {

            $result = [];
            foreach ($array1 as $k){
                $temu = 0;
                foreach ($array2 as $i) {
                    if($k == $i){
                        $temu = 1;
                        break;
                    }
                }
                if($temu == 0){
                    $result[] = $k;
                }
            }
            return $result;
        }
    }

    if(!function_exists('parse_token_keysearch')){
        function parse_token_keysearch($text){
            $p = explode(' ',$text);
            $ret = [];
            $ret[] = trim($text);

            $jmltoken = count($p);
            if($jmltoken > 2) {
                do {
                    $buff = '';

                    for ($i = 0; $i < $jmltoken; $i++) {
                        $buff .= ($buff == '' ? '' : ' ') . $p[$i];
                        if ($i == ($jmltoken - 1)) {
                            $ret[] = $buff;
                        }
                    }
                    $jmltoken--;
                } while ($jmltoken > 1);
            }

            foreach ($p as $item) {
                $ret[] = $item;
            }
            return $ret;
        }
    }

    if(!function_exists('highlight_text')){
        function highlight_text($text, $search){
            $lower = strtolower($text);
            $s = is_array($search) ? $search : parse_token_keysearch($search);
            $buff = $text;

            foreach($s as $k){
                $k = strtolower(trim($k));
                if($k != '') {
                    $lenkw = strlen($k);
                    $offset = 0;
                    $pos = strpos($lower, $k, $offset);
                    while ($pos > 0) {
                        $cariem = strpos( substr($buff, $offset, $pos-$offset),'<em>') ;
                        if( $cariem != false ) {
                            $cariendenm = strpos( substr($buff, $cariem+4, $pos-($cariem+4)),'</em>');
                            if($cariendenm == false){break;}
                        }

                        $newbuff = substr($buff, $offset, $pos - $offset) .
                            '<em>'.
                            substr($buff, $pos, $lenkw) .
                            '</em>' .
                            substr($buff, $pos + $lenkw);
                        $buff = $newbuff;
                        $offset = $pos + $lenkw + 9;
                        $lower = strtolower($buff);
                        if(strlen($lower) > $offset) {
                            $pos = strpos($lower, $k, $offset);
                        }else{
                            $pos = 0;
                        }
                    }
                }
            }
            return $buff == '' ? $text : $buff;
        }
    }
    if(!function_exists('crop_text_search')){
        function crop_text_search($text, $search, $numChar = 150){
            $s = is_array($search) ? $search : parse_token_keysearch($search);
            $s2 = $s;

            $lowcase = strtolower($text);
            $lentxt = strlen($lowcase);
            $offset = 0;
            $buff = '';
            foreach ($s as $idx=>$k){
                $k = trim(strtolower($k));
                $lenkw = strlen($k);
                $pos = strpos($lowcase, $k , $offset);

                if($pos != false){
                    $ca  =  $pos - ( ($numChar/2) - $lenkw);
                    $awal = max($ca, 0);
                    $cz   = $pos + $lenkw + ($numChar/2);
                    $akhir = min($cz, $lentxt);
                    return substr($text, $awal, $akhir - $awal);
                }
            }
            return substr($text,0,$numChar);
        }
    }



function escapeString($input) {
    // Hapus karakter berbahaya menggunakan regex
    $pattern = '/[\'"\\\x00\n\r\x1a]/u';
    $replacement = '\\\\$0';
    $escaped_input = preg_replace($pattern, $replacement, $input);

    return $escaped_input;
}

