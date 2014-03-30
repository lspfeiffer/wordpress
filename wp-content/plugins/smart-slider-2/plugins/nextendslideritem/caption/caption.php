<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemCaption extends plgNextendSliderItemAbstract {

    var $_identifier = 'caption';

    var $_title = 'Caption - full';

    function getTemplate() {
        return "
          <div>
              Only in full version!
          </div>
        ";
    }

    function getValues() {
        return array(
            'link' => '#|*|_self',
            'url' => '',
            'target' => '_self',
            'image' => NextendSmartSliderSettings::get('placeholder'),
            'alt' => 'Image not available',
            'content' => 'Title',
            'description' => 'Here comes the description text.',
            'fontclass' => 'sliderfont11',
            'fontclasstitle' => 'sliderfont1',
            'captionclass' => 'simple-bottom',
            'width' => '130',
            'height' => '120',
            'color' => '00000080',
            'colora' => 'RGBA(0,0,0,0.6)',
            'colorhex' => '000000',
            'customcaptionclass' => 'my-caption-custom-class'
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemCaption');