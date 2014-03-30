<?php

class plgNextendSliderTypeSimple extends NextendPluginBase {
    
    var $_name = 'simple';
    
    function onNextendSliderTypeList(&$list){
        $list[$this->_name] = $this->getPath();
    }
    
    static function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.'simple'.DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidertype', 'plgNextendSliderTypeSimple');