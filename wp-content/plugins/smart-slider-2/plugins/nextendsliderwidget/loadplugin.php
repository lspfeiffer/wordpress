<?php
defined('NEXTENDLIBRARY') or die();

$dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
foreach(NextendFilesystem::folders($dir) AS $folder){
    $file = $dir.$folder.DIRECTORY_SEPARATOR.$folder.'.php';
    if(NextendFilesystem::fileexists($file)){
        require_once($file);
    }
}
