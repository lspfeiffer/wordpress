<?php
nextendimport('nextend.cache.cache');

class NextendCacheCss extends NextendCache {

    function NextendCacheCss() {
        $this->_subfolder = 'css' . DIRECTORY_SEPARATOR;
        parent::NextendCache();
        $this->_filetype = 'css';
        $this->_gzip = getNextend('gzip', 0);
    }

    function parseFile($content, $path, $i) {
        return preg_replace('#url\([\'"]([^"\'\)]+)[\'"]\)#', 'url(' . str_replace(array('http://', 'https://'), '//', NextendFilesystem::pathToAbsoluteURL(dirname($path))) . '/$1)', $content);
    }    
    
    function parseCached($cached){
        return $cached;
    }

    function getContentHeader() {
        return 'header("Content-type: text/css", true);';
    }
}