<?php

class plgNextendSliderWidgetBar extends NextendPluginBase {

    var $_group = 'bar';

    function onNextendSliderWidgetList(&$list) {
        $list[$this->_group] = array(NextendText::_('Bar'), $this->getPath(), 5);
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bar' . DIRECTORY_SEPARATOR;
    }
}
NextendPlugin::addPlugin('nextendsliderwidget', 'plgNextendSliderWidgetBar');