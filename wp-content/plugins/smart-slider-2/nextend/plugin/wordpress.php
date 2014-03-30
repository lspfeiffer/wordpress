<?php
nextendimport('nextend.data.data');

class NextendPluginBase{
    
    var $params = null;
    
    function __construct(){
        $this->params = new NextendData();
        $this->loadConfig();
    }
    
    function loadConfig(){
        $this->params->loadArray(get_option(get_class($this)));
    }
}

class NextendPlugin extends NextendPluginAbstract{

}