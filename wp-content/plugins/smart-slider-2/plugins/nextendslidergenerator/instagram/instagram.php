<?php
nextendimportsmartslider2('nextend.smartslider.settings');
nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorInstagram extends NextendPluginBase {

    public static $_group = 'instagram';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        if($showall || smartsliderIsFull()){
            $group[self::$_group] = 'Instagram';
    
            if (!isset($list[self::$_group])) $list[self::$_group] = array();
    	
    	      $configured = is_string(NextendSmartSliderStorage::get(self::$_group));
    	
            $list[self::$_group][self::$_group . '_myfeed'] = array(NextendText::_('My_feed'), $this->getPath() . 'myfeed' . DIRECTORY_SEPARATOR, $configured, true, true);
            $list[self::$_group][self::$_group . '_tagsearch'] = array(NextendText::_('Search_by_tag'), $this->getPath() . 'tagsearch' . DIRECTORY_SEPARATOR, $configured, true, true);
            $list[self::$_group][self::$_group . '_myphotos'] = array(NextendText::_('My photos'), $this->getPath() . 'myphotos' . DIRECTORY_SEPARATOR, $configured, true, true);
        }
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorInstagram');