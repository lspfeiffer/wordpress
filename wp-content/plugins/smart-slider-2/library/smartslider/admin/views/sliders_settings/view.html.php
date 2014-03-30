<?php

class NextendSmartsliderAdminViewSliders_Settings extends NextendView {

    var $xml = 'default';

    function layoutAction($tpl) {
        $this->xml = 'layout';
        $this->render($tpl);
    }

    function fontAction($tpl) {
        $this->xml = 'font';        
        $this->render('font');
    }

    function joomlaAction($tpl) {
        $this->xml = 'joomla';
        $this->render($tpl);
    }
    
    function pluginAction($tpl){
        $plugin = NextendRequest::getVar('action');
        $path = null;
        
        NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendGeneratorConfiguration', array(&$plugin, &$path));
        if($path){
            $path.='configuration.xml';
            
            $this->xml = $path;
            $this->group = $plugin;
            $this->render('plugin');
        }else{
            $this->defaultAction($tpl);
        }
    }

}
