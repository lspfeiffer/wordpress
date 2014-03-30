<?php

class NextendRequest{
    
    static function set($var, $val){
        $_REQUEST[$var] = $val;
    }
    
    static function getVar($var, $default = null){
        $val = isset($_REQUEST[$var]) ? $_REQUEST[$var] : $default;
        return $val;
    }
    
    static function getInt($var, $default = 0){
        $val = isset($_REQUEST[$var]) ? $_REQUEST[$var] : $default;
        return intval($val);
    }
    static function getCmd($var, $default = ''){
        $val = isset($_REQUEST[$var]) ? $_REQUEST[$var] : $default;
        return preg_replace("/[^\w_]/", "", $val);
    }
}
?>
