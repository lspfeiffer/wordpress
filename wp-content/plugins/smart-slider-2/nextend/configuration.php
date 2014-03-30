<?php
global $nextend;

$nextend = array(
    'cachetime' => 'static',
    'cachepath' => null,
    'gzip' => 0,
    'debuglng' => 0,
    'logproblems' => 1
);

function getNextend($prop, $default = ''){
    global $nextend;
    if(isset($nextend[$prop]) && $nextend[$prop] !== null) return $nextend[$prop];
    return $default;
}

function setNextend($prop, $value){
    global $nextend;
    $nextend[$prop] = $value;
}