<?php

nextendimport('nextend.cache.data.data');
class NextendCacheData extends NextendCacheDataAbstract {

    // $time in minutes
    function cache($group = '', $time = 1440, $callable = null, $params = null) {
        $obhash = '';
        if(is_array($callable)){
            if(isset($callable[0]) && is_object($callable[0])) $obhash.= get_class($callable[0]);
            if(isset($callable[1]) && is_string($callable[1]))  $obhash.= $callable[1];
        }
        $hash = 'ss2_'.md5($group.$obhash.json_encode($params));
        
        if ( false === ( $data = get_transient( $hash ) ) ) {
            if (!is_array($params)){
                $params = !empty($params) ? array(&$params) : array();
            }
            $data = call_user_func_array($callable, $params);
            set_transient($hash, $data, $time * 60);
        }        
        return $data;
    }
}