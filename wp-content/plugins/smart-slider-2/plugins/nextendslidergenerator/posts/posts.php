<?php

nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorPosts extends NextendPluginBase {

    var $_group = 'posts';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        if($showall || smartsliderIsFull()){
            $group[$this->_group] = 'Posts';
    
            if (!isset($list[$this->_group])) $list[$this->_group] = array();
            $list[$this->_group][$this->_group . '_posts'] = array('By filter', $this->getPath() . 'posts' . DIRECTORY_SEPARATOR, true, true, true, 'article');
        }
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorPosts');