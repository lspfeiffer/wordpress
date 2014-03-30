<?php
nextendimportsmartslider2('nextend.smartslider.settings');
nextendimportsmartslider2('nextend.smartslider.check');

class plgNextendSliderGeneratorFacebook extends NextendPluginBase {

    public static $_group = 'facebook';

    function onNextendSliderGeneratorList(&$group, &$list, $showall = false) {
        if($showall || smartsliderIsFull()){
            $group[self::$_group] = 'Facebook';
    
            if (!isset($list[self::$_group])) $list[self::$_group] = array();
    	
    	      $configured = is_string(NextendSmartSliderStorage::get(self::$_group));
    	
            $list[self::$_group][self::$_group . '_postsbypage'] = array(NextendText::_('Posts_by_page'), $this->getPath() . 'postsbypage' . DIRECTORY_SEPARATOR, $configured, true, true);
            $list[self::$_group][self::$_group . '_albumbypage'] = array(NextendText::_('Photos_by_page_album'), $this->getPath() . 'albumbypage' . DIRECTORY_SEPARATOR, $configured, true, true);
            $list[self::$_group][self::$_group . '_albumbyuser'] = array(NextendText::_('Photos_by_user_album'), $this->getPath() . 'albumbyuser' . DIRECTORY_SEPARATOR, $configured, true, true);
        }
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslidergenerator', 'plgNextendSliderGeneratorFacebook');