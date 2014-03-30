<?php

nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorImageFromFolder extends NextendPluginBase {

    var $_group = 'imagefromfolder';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        $group[$this->_group] = 'Image';

        if (!isset($list[$this->_group])) $list[$this->_group] = array();
        $list[$this->_group][$this->_group . '_fromfolder'] = array(NextendText::_('From_folder'), $this->getPath() . 'fromfolder' . DIRECTORY_SEPARATOR, true, false, true, 'image');
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorImageFromFolder');