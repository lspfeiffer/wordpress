<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemShape extends plgNextendSliderItemAbstract {

    var $_identifier = 'shape';

    var $_title = 'Shape - full';

    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }

    function getValues() {
        return array(
            'width' => '100',
            'height' => '100',
            'color' => '00000080',
            'colora' => 'RGBA(0,0,0,0.6)',
            'colorhex' => '000000'
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemShape');