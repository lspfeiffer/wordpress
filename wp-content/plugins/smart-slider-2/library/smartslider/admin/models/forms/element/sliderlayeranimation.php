<?php

nextendimport('nextend.form.element.list');

class NextendElementSliderLayerAnimation extends NextendElementList {

    function fetchElement() {
        static $options;
        if(!$options){
            $options = array(
                0 => 'No_animation',
                '1' => 'Fade - full',
                '2' => 'Slide left to right - full',
                '3' => 'Slide right to left - full',
                '4' => 'Slide top to bottom - full',
                'slidebottomtotop' => 'Slide bottom to top',
                '5' => 'Flip X - full',
                '6' => 'Fade up - full',
                'fadedown' => 'Fade down',
                '8' => 'Fade left - full',
                '9' => 'Fade right - full',
                '10' => 'Bounce - full',
                '11' => 'Rotate - full',
                '12' => 'Rotate up left - full',
                '13' => 'Rotate down left - full',
                '13' => 'Rotate up right - full',
                '14' => 'Rotate down right - full',
                'rollin' => 'Roll in',
                '15' => 'Roll out - full',
                '16' => 'Scale - full'
            );
            NextendPlugin::callPlugin('nextendslider', 'onNextendSliderLayerAnimations', array(&$options));
        }

        if (count($options)) {
            foreach ($options AS $k => $v) {
                $this->_xml->addChild('option', $v)->addAttribute('value', $k);
            }
        }
        $this->_value = $this->_form->get($this->_name, $this->_default);
        $html = parent::fetchElement();
        return $html;
    }

}
