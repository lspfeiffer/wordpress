<?php

if( !function_exists('json_encode') ) {
    nextendimport('nextend.json.external.json');
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}


if( !function_exists('json_decode') ) {
    nextendimport('nextend.json.external.json');
    function json_decode($data, $assoc = false) {
      $use = 0;
      if($assoc) $use = SERVICES_JSON_LOOSE_TYPE; 
      $json = new Services_JSON($use);
      return( $json->decode($data) );
    }
}