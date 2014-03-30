<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemFlipper extends plgNextendSliderItemAbstract {

    var $_identifier = 'flipper';

    var $_title = 'Flipper - full';

    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }

    function getValues() {
        return array(
            'imagefront' => NextendSmartSliderSettings::get('placeholder'),
            'imageback' => NextendSmartSliderSettings::get('placeholder'),
            'alt' => 'Image not available',
            'link' => '#|*|_self',
            'url' => '',
            'target' => '_self',
            'width' => '100%',
            'flipclass' => 'myflip',
            'css' => '',
            'class' => ''
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemFlipper');