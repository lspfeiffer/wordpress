<?php

class NextendUriAbstract{
    var $_baseuri;
    
    static function getInstance() {

        static $instance;
        if (!is_object($instance)) {
            $instance = new NextendUri();
        } // if

        return $instance;
    }
    
    static function setBaseUri($uri){
        $i = NextendUri::getInstance();
        $i->_baseuri = $uri;
    }
    
    static function getBaseUri(){
        $i = NextendUri::getInstance();
        return $i->_baseuri;
    }
    
    static function pathToUri($path){
        $i = NextendUri::getInstance();
        return $i->_baseuri.str_replace(array(NextendFilesystem::getBasePath(),DIRECTORY_SEPARATOR),array('','/'), str_replace('/',DIRECTORY_SEPARATOR,$path));
    }
    
    static function ajaxUri($query = '', $magento = 'nextendlibrary'){
        $i = NextendUri::getInstance();
        return $i->_baseuri;
    }
}

if (nextendIsJoomla()) {
    nextendimport('nextend.uri.joomla');
} elseif (nextendIsWordPress()) {
    nextendimport('nextend.uri.wordpress');
} elseif (nextendIsMagento()) {
    nextendimport('nextend.uri.magento');
}
