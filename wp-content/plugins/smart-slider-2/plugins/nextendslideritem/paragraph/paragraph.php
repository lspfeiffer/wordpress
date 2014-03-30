<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemParagraph extends plgNextendSliderItemAbstract {
    
    var $_identifier = 'paragraph';
    
    var $_title = 'Paragraph - full';
    
    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }
    
    function getValues(){
        return array(
            'fontsizer' => '',
            'fontcolorr' => '',
            'content' => 'Empty paragraph...',
            'fontclass' => 'sliderfont6',
            'class' => '',
            'css' => ''
            
        );
    }
    
    function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.$this->_identifier.DIRECTORY_SEPARATOR;
    } 
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemParagraph');