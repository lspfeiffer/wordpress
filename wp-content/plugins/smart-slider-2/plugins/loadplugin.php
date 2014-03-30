<?php
defined('NEXTENDLIBRARY') or die();

nextendimport('nextend.plugin.plugin');

$mdir = dirname(__FILE__).DIRECTORY_SEPARATOR;
foreach(NextendFilesystem::folders($mdir) AS $mfolder){
    $mfile = $mdir.$mfolder.DIRECTORY_SEPARATOR.'loadplugin.php';
    if(NextendFilesystem::fileexists($mfile)){
        require_once($mfile);
    }
}
