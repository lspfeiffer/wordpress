<?php
nextendimportsmartslider2('nextend.smartslider.settings');
nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorTwitter extends NextendPluginBase {

    public static $_group = 'twitter';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        if($showall || smartsliderIsFull()){
            $group[self::$_group] = 'Twitter';
    
            if (!isset($list[self::$_group])) $list[self::$_group] = array();
    	
            $configured = is_string(NextendSmartSliderStorage::get(self::$_group));
    	
            $list[self::$_group][self::$_group . '_timeline'] = array(NextendText::_('Timeline'), $this->getPath() . 'twittertimeline' . DIRECTORY_SEPARATOR, $configured, true, true);
        }
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorTwitter');