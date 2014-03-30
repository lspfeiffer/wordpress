<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemiframe extends plgNextendSliderItemAbstract {
    
    var $_identifier = 'iframe';
    
    var $_title = 'iframe - full';
    
    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }
    
    function getValues(){
        return array(
            'url' => 'about:blank',
            'width' => '100%',
            'height' => '100%',
            'scroll' => 'yes'
        );
    }
    
    function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.$this->_identifier.DIRECTORY_SEPARATOR;
    } 
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemiframe');