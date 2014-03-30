<?php
nextendimportsmartslider2('nextend.smartslider.settings');
nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorYoutube extends NextendPluginBase {

    public static $_group = 'youtube';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        if($showall || smartsliderIsFull()){
            $group[self::$_group] = 'YouTube';
    
            if (!isset($list[self::$_group])) $list[self::$_group] = array();
    	
    	      $configured = is_string(NextendSmartSliderStorage::get(self::$_group));
    	
            $list[self::$_group][self::$_group . '_bysearch'] = array('By search', $this->getPath() . 'bysearch' . DIRECTORY_SEPARATOR, $configured, true, true);
            $list[self::$_group][self::$_group . '_byplaylist'] = array('By playlist', $this->getPath() . 'byplaylist' . DIRECTORY_SEPARATOR, $configured, true, true);
        }
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorYoutube');