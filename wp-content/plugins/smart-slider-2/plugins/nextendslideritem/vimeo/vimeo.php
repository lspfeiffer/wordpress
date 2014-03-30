<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemVimeo extends plgNextendSliderItemAbstract {
    
    var $_identifier = 'vimeo';
    
    var $_title = 'Vimeo - full';
    
    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }
    
    function getValues(){
        return array(
            'code' => '75251217',
            'autoplay' => 0,
            'title' => 1,
            'byline' => 1,
            'portrait' => 0,
            'color' => '00adef',
            'loop' => 0
        );
    }
    
    function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.$this->_identifier.DIRECTORY_SEPARATOR;
    } 
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemVimeo');