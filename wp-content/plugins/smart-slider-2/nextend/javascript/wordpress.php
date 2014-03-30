<?php

class NextendJavascriptWordPress extends NextendJavascript {
    function generateJs(){
        $this->generateLibraryJs();
        if($this->_cacheenabled){
            if (count($this->_jsFiles)) {
                foreach($this->_jsFiles AS $file) {
                    if(substr($file, 0, 4) == 'http'){
                    $this->serveJsFile($file);
                    }else{
                        $this->_cache->addFile($file);
                    }
                }
            }
            $this->_cache->addInline($this->_js);
            $filename = $this->_cache->getCache();
            if($filename){
                $this->serveJsFile($filename);
            }
        }else{
            if(count($this->_jsFiles)){
                foreach($this->_jsFiles AS $file){
                    //$document->addScript(NextendUri::pathToUri($file));
                }
            }
            $this->serveJs();
        }
        $this->serveInlineJs();
    }
    
    function serveJsFile($url){
        echo '<script type="text/javascript" src="'.$url.'"></script>';
    }
}