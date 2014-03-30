<?php

class NextendParse {
    
    function fromMixed($s) {

        return explode('|*|', $s);
    }
    
    static function fromArray($s) {

        return explode('||', $s);
    }
    
    static function parse($s, $concat = false) {

        $v = explode("|*|", $s);
        for ($i = 0;$i < count($v);$i++) {
            if (strpos($v[$i], "||") !== false) {
                if ($concat === false) $v[$i] = explode("||", $v[$i]);
                else $v[$i] = str_replace("||", $concat, $v[$i]);
            }
        }
        if ($v[count($v) - 1] == '') unset($v[count($v) - 1]);
        return count($v) == 1 ? $v[0] : $v;
    }
    
    static function parseUnit($v, $concat = '') {

        if (!is_array($v)) $v = self::parse($v);
        $unit = $v[count($v) - 1];
        unset($v[count($v) - 1]);
        $r = '';
        foreach($v AS $m) {
            $r.= $m . $unit . $concat;
        }
        return $r;
    }
}
