<?php
nextendimportsmartslider2('nextend.smartslider.settings');
nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorFlickr extends NextendPluginBase {
    public static $_group = 'flickr';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        if($showall || smartsliderIsFull()){
            $group[self::$_group] = 'Flickr';
    
            if (!isset($list[self::$_group])) $list[self::$_group] = array();
    	
    	      $configured = is_string(NextendSmartSliderStorage::get(self::$_group));
    	
            $list[self::$_group][self::$_group . '_peoplephotostream'] = array(NextendText::_('My_photostream'), $this->getPath() . 'peoplephotostream' . DIRECTORY_SEPARATOR, $configured, true, true);
            $list[self::$_group][self::$_group . '_peoplephotoset'] = array(NextendText::_('My_photoset'), $this->getPath() . 'peoplephotoset' . DIRECTORY_SEPARATOR, $configured, true, true);
            $list[self::$_group][self::$_group . '_peoplephotogallery'] = array(NextendText::_('My_gallery'), $this->getPath() . 'peoplephotogallery' . DIRECTORY_SEPARATOR, $configured, true, true);
        }
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorFlickr');