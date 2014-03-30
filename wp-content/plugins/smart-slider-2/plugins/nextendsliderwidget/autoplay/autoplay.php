<?php

class plgNextendSliderWidgetAutoplay extends NextendPluginBase {

    var $_group = 'autoplay';

    function onNextendSliderWidgetList(&$list) {
        $list[$this->_group] = array(NextendText::_('Autoplay'), $this->getPath(), 3);
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoplay' . DIRECTORY_SEPARATOR;
    }
}
NextendPlugin::addPlugin('nextendsliderwidget', 'plgNextendSliderWidgetAutoplay');