<?php

class plgNextendSliderWidgetHTML extends NextendPluginBase {

    var $_group = 'html';

    function onNextendSliderWidgetList(&$list) {
        $list[$this->_group] = array(NextendText::_('HTML'), $this->getPath(), 10);
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR;
    }
}
NextendPlugin::addPlugin('nextendsliderwidget', 'plgNextendSliderWidgetHTML');