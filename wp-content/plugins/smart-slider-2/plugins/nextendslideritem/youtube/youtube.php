<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemYoutube extends plgNextendSliderItemAbstract {
    
    var $_identifier = 'youtube';
    
    var $_title = 'YouTube - full';
    
    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }
    
    function getValues(){
        return array(
            'code' => '_2jVG9Cihxs',
            'youtubeurl' => 'http://www.youtube.com/watch?v=_2jVG9Cihxs',
            'autoplay' => 0,
            'defaultimage' => 'maxresdefault',
            'related' => '1',
            'vq' => 'default'
        );
    }
    
    function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.$this->_identifier.DIRECTORY_SEPARATOR;
    } 
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemYoutube');