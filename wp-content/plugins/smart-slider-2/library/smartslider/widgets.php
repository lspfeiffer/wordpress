<?php
class NextendSliderWidgets {

    var $_slider;

    var $_id;

    var $_enabledWidgets;

    var $_widgets;

    function NextendSliderWidgets($slider, $id) {
        $this->_slider = $slider;
        $this->_id = $id;
        $this->_widgets = array();
        $this->_enabledWidgets = array();

        $params = $this->_slider->_sliderParams;
        if($slider->_backend) return;
        $plugins = array();
        
        NextendPlugin::callPlugin('nextendsliderwidget', 'onNextendSliderWidgetList', array(&$plugins));

        foreach ($plugins AS $k => $v) {
            $widget = $params->get('widget'.$k);
            $display = NextendParse::parse($params->get('widget'.$k.'display', '0|*|always|*|0|*|0'));
            if($widget != '' && (isset($display[0]) && intval($display[0]) || isset($display[2]) && intval($display[2]) || isset($display[3]) && intval($display[3]))){
                $this->_enabledWidgets[$k] = $widget;
            }
        }
        foreach($this->_enabledWidgets AS $k => $v){
            if(nextendIsJoomla()) JPluginHelper::importPlugin('nextendsliderwidget'.$k);
            $class = 'plgNextendSliderWidget'.$k.$v;
            if(class_exists($class, false)) $this->_widgets[$k] = call_user_func(array($class, 'render'), $slider, $id, $params);
        }

    }

    function echoOnce($k){
        if(isset($this->_widgets[$k])){
            echo $this->_widgets[$k];
            unset($this->_widgets[$k]);
        }
    }

    function echoOne($k){
        if(isset($this->_widgets[$k])){
            echo $this->_widgets[$k];
        }
    }

    function echoRemainder(){
        foreach($this->_widgets AS $v){
            echo $v;
        }
    }
}