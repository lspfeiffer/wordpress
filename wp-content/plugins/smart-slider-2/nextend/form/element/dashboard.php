<?php

class NextendElementDashboard extends NextendElement {
    
    function fetchTooltip() {
        return '';
    }
    
    function fetchElement() {
        $html = '';
        $version = (string)$this->_form->_xml->version;
        $url = NextendXmlGetAttribute($this->_xml, 'url').'&version='.$version;
        $html.= '<iframe width="100%" frameborder="no" style="border: 0px; height: 150px;" src="'.$url.'"></iframe>';
        return $html;
    }
}
