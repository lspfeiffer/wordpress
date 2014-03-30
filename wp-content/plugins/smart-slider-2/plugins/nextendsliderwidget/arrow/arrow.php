<?php

class plgNextendSliderWidgetArrow extends NextendPluginBase {

    var $_group = 'arrow';

    function onNextendSliderWidgetList(&$list) {
        $list[$this->_group] = array(NextendText::_('Arrows'), $this->getPath(), 1);
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'arrow' . DIRECTORY_SEPARATOR;
    }
}
NextendPlugin::addPlugin('nextendsliderwidget', 'plgNextendSliderWidgetArrow');