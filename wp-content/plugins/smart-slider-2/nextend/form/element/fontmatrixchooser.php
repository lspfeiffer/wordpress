<?php
nextendimport('nextend.form.element.list');

class NextendElementFontMatrixChooser extends NextendElementList {
    
    function fetchElement() {
        
        if(isset($_REQUEST['get'])){
            $get = json_decode((get_magic_quotes_gpc() || nextendIsWordPress() ? stripslashes($_REQUEST['get']) : $_REQUEST['get']), true);
            if(isset($get['sliderid'])){
                nextendimportsmartslider2('nextend.smartslider.settings');
                NextendSmartSliderFontSettings::initAdminFonts(intval($get['sliderid']));
            }
        }
        
        $this->_xml->addChild('option', 'None')->addAttribute('value', '');
        
        if(isset($GLOBALS['nextendfontmatrix']) && count($GLOBALS['nextendfontmatrix'])){
            foreach($GLOBALS['nextendfontmatrix'] as $k => $v) {
                $this->_xml->addChild('option', $v)->addAttribute('value', $k);
            }
        }
        return parent::fetchElement();
    }
}

