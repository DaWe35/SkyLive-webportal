<?php

function substrhtml($str,$start,$len) {
    $str_clean = substr(strip_tags($str),$start,$len);
    $pos = strrpos($str_clean, " ");
    if ($pos === false) {
        $str_clean = substr(strip_tags($str),$start,$len);  
    } else {
        $str_clean = substr(strip_tags($str),$start,$pos);
    }
    if (preg_match_all('/\<[^>]+>/is',$str,$matches,PREG_OFFSET_CAPTURE)) {
        for ($i=0;$i<count($matches[0]);$i++) {
            if ($matches[0][$i][1] < $len) {
                $str_clean = substr($str_clean,0,$matches[0][$i][1]) . $matches[0][$i][0] . substr($str_clean,$matches[0][$i][1]);
             } else if (preg_match('/\<[^>]+>$/is',$matches[0][$i][0])) {
                $str_clean = substr($str_clean,0,$matches[0][$i][1]) . $matches[0][$i][0] . substr($str_clean,$matches[0][$i][1]);
                break;
            }
        }
        return $str_clean;
    } else {
        $string = substr($str,$start,$len);
        $pos = strrpos($string, " ");
        if ($pos === false) {
            return substr($str,$start,$len);
        }
            return substr($str,$start,$pos);
    }
}