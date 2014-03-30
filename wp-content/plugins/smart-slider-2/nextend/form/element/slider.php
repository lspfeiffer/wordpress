<?php
nextendimport('nextend.form.element.list');

class NextendElementSlider extends NextendElementList {
    
    function fetchElement() {
    
        $this->_xml->addChild('option', 'Choose a slider')->addAttribute('value', 0);
            
        $options = $this->getOptions();
        for($i = 0; $i < count($options); $i++){
            $this->_xml->addChild('option', htmlspecialchars($options[$i]['title']))->addAttribute('value', $options[$i]['id']);
        }
        
        $this->_value = $this->_form->get($this->_name, $this->_default);
        
        return parent::fetchElement();
    }
    
    function getOptions(){
        nextendimport('nextend.smartslider.admin.models.sliders');
        $slidersModel = new NextendSmartsliderAdminModelSliders(null);
        return $slidersModel->getSliders();
    }
    
}