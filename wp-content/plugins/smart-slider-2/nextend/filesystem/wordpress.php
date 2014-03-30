<?php

class NextendFilesystem extends NextendFilesystemAbstract{
    
    function NextendFilesystem(){
        $this->_basepath = realpath(WP_CONTENT_DIR);
        $this->_cachepath = $this->_basepath.DIRECTORY_SEPARATOR.getNextend('cachepath', 'cache'.DIRECTORY_SEPARATOR);
        $this->_librarypath = str_replace($this->_basepath, '', NEXTENDLIBRARY);
    }
}