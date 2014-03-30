<?php

class NextendElementClearcache extends NextendElement {
    
    function fetchElement() {
        $html = '<a href="'.$_SERVER['REQUEST_URI'].'&nextendclearcache=1" class="nextend-button-css nextend-font-export">'.NextendText::_($this->_label).'</a>';
        return $html;
    }
}
