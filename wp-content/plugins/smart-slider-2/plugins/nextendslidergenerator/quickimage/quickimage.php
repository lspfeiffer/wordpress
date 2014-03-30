<?php

nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorQuickImage extends NextendPluginBase {

    var $_group = 'imagefromfolder';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        $group[$this->_group] = 'Image';

        if (!isset($list[$this->_group])) $list[$this->_group] = array();
        $list[$this->_group][$this->_group . '_quickimage'] = array(NextendText::_('Quick image'), $this->getPath() . 'quickimage' . DIRECTORY_SEPARATOR, true, false, true, 'image_quick');
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorQuickImage');