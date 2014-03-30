<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemHtml extends plgNextendSliderItemAbstract {
    
    var $_identifier = 'html';
    
    var $_title = 'HTML - full';
    
    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }
    
    function getValues(){
        return array(
            'html' => '<div class="myfirstclass">
My HTML element
</div>',
            'css' => '.myfirstclass {
height: 30px;
line-height: 30px; 
background: royalblue;
color: white;
text-align: center;
}'
        );
    }
    
    function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.$this->_identifier.DIRECTORY_SEPARATOR;
    } 
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemHtml');