<?php

nextendimport('nextend.form.element.list');

class NextendElementWordpressmenuitems extends NextendElementList {

    var $_menutype = 'mainmenu';

    function fetchElement() {
        $menu = explode('|*|', $this->parent->_value);
        $this->_menutype = $menu[0];
        
        $items = wp_get_nav_menu_items( $this->_menutype, array() );
        $this->_xml->addChild('option', 'Root')->addAttribute('value', 0);
        $pre = array();
        if (is_array($items) && count($items)) {
            foreach ($items AS $item) {
                if(!isset($pre[$item->ID])){
                    if(isset($pre[$item->menu_item_parent])){
                        $pre[$item->ID] = $pre[$item->menu_item_parent].'- ';
                    }else{
                        $pre[$item->ID] = '- ';
                    }
                }
                $this->_xml->addChild('option', $pre[$item->ID].htmlspecialchars($item->title))->addAttribute('value', $item->ID);
            }
        }
        $this->_value = $this->_form->get($this->_name, $this->_default);
        $html = parent::fetchElement();
        return $html;
    }

}
