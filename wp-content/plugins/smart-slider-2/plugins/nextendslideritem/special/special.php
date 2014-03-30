<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemSpecial extends plgNextendSliderItemAbstract {

    var $_identifier = 'special';

    var $_title = 'Special - full';

    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }

    function getValues() {
        return array(
            'html' => '',
            'skins' => '',
            'skin' => ''
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemSpecial');