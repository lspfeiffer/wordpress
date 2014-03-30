<?php
nextendimport('nextend.form.tab');
nextendimport('nextend.form.tabs.tabbed');

class NextendTabGroupped extends NextendTabTabbed {

    var $_tabs;
    
    function render($control_name) {
        $this->initTabs();      
        foreach($this->_tabs AS $tabname => $tab) {
            $tab->render($control_name);
        }
    }
}