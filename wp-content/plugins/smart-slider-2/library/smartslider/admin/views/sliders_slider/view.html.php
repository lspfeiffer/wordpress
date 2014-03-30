<?php

class NextendSmartsliderAdminViewSliders_slider extends NextendView{
    
    function fontAction($tpl) {
        $this->xml = 'font';        
        $this->render('font');
    }
}
?>
