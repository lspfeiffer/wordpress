<?php

nextendimport('nextend.form.element.text');

class NextendElementDate extends NextendElementText {

    function fetchElement() {

        $css = NextendCss::getInstance();
        $css->addCssLibraryFile('calendar.css');
        $js = NextendJavascript::getInstance();
        $js->addJsAssetsFile('datetimepicker_css.js');
        
        $html = parent::fetchElement();
        $html.= '<div class="nextend-calendar" onclick="javascript:NewCssCal(\''.$this->_id.'\', \'yyyyMMdd\',\'dropdown\',true, 24)"></div>';
        return $html;
    }
}
