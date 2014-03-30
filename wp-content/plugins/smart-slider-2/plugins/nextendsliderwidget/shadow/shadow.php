<?php

class plgNextendSliderWidgetShadow extends NextendPluginBase {

    var $_group = 'shadow';

    function onNextendSliderWidgetList(&$list) {
        $list[$this->_group] = array(NextendText::_('Shadows'), $this->getPath(), 7);
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'shadow' . DIRECTORY_SEPARATOR;
    }
}
NextendPlugin::addPlugin('nextendsliderwidget', 'plgNextendSliderWidgetShadow');