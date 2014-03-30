<?php
nextendimport('nextend.form.element.mixed');
nextendimport('nextend.form.element.wordpressmenu');
nextendimport('nextend.form.element.wordpressmenuitems');

class NextendElementWordpressMenuWithItems extends NextendElementMixed {
    function fetchElement() {
        
        $js = NextendJavascript::getInstance();
        $js->addLibraryJsAssetsFile('dojo', 'element.js');
        $js->addLibraryJsAssetsFile('dojo', 'element/menuwithitems.js');
        
        $html = '';
        
        $this->_value = $this->_form->get($this->_name, $this->_default);
        
        
        $html.= parent::fetchElement();
        
        $groupedList = array();
        $menus = get_terms('nav_menu');
        foreach($menus as $menu){
            $groupedList[$menu->term_id] = array();
            $items = wp_get_nav_menu_items( $menu->term_id, array() );
            $pre = array();
            if (count($items)) {
                foreach ($items AS $item) {
                    if(!isset($pre[$item->ID])){
                        if(isset($pre[$item->menu_item_parent])){
                            $pre[$item->ID] = $pre[$item->menu_item_parent].'- ';
                        }else{
                            $pre[$item->ID] = '- ';
                        }
                    }
                    $groupedList[$menu->term_id][] = array($item->ID, $pre[$item->ID].htmlspecialchars($item->title));
                }
            }
        }
        
        $js->addLibraryJs('dojo', '
            new NextendElementMenuWithItems({
              hidden: "' . $this->_id . '",
              options: ' . json_encode($groupedList) . ',
              value: "'.$this->_value.'"
            });
        ');
        
        return $html;
    }
}