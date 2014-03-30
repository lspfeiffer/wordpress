<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemTag extends plgNextendSliderItemAbstract {

    var $_identifier = 'tag';

    var $_title = 'Tag - full';
    
    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }

    function getValues() {
        return array(
            'class' => '',
            'tagclass' => 'tagclass',
            'url' => '#',
            'target' => '_self',
            'content' => 'mytag',
            'fontclass' => 'sliderfont7',
            'color' => '#357cbd',
            'hovercolor' => '#01add3'
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemTag');