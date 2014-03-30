<?php

nextendimportsmartslider2('nextend.smartslider.slider');
nextendimport('nextend.data.data');
nextendimport('nextend.parse.parse');

class NextendSliderWordpress extends NextendSlider{

    var $_data;
    
    var $_sliderid;

    function NextendSliderWordpress($sliderid, &$params, $path, $backend = false) {
        parent::NextendSlider($path, $backend);
        
        $this->_sliderid = $sliderid;
    }
    
    function preRender(){
        
        $this->loadSlider($this->_sliderid);
        
        $this->setTypePath();
        $this->setInstance();
    }

    function setTypePath() {
        $type = $this->_slider->get('type', 'default');
        
        $class = 'plgNextendSlidertype' . $type;
        if (!class_exists($class, false)) {
            echo 'Error in slider type!';
            return false;
        }
        $this->_typePath = call_user_func(array($class, "getPath"));
    }
}