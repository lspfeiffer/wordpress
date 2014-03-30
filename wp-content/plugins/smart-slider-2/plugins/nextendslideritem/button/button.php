<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemButton extends plgNextendSliderItemAbstract {
    
    var $_identifier = 'button';
    
    var $_title = 'Button';
    
    function getTemplate(){
        return "
<div class=\"nextend-smartslider-button-{buttonclass}-container {fontclass}\" style=\"cursor:pointer; width: 100%;\">
    <a href=\"{url}\" target=\"{target}\" style=\"display: block;\" class=\"nextend-smartslider-button-{buttonclass} {class}\">
      {content}
    </a>
</div>
<style type=\"text/css\">
    div#{{id}} div.nextend-smartslider-button-{buttonclass}-container a.nextend-smartslider-button-{buttonclass}{
        {css}
    }
    
    div#{{id}} div.nextend-smartslider-button-{buttonclass}-container a.nextend-smartslider-button-{buttonclass}:HOVER,
    div#{{id}} div.nextend-smartslider-button-{buttonclass}-container a.nextend-smartslider-button-{buttonclass}:FOCUS,
    div#{{id}} div.nextend-smartslider-button-{buttonclass}-container a.nextend-smartslider-button-{buttonclass}:ACTIVE{
        {csshover}
    }
</style>
        ";
    }
    
    function _render($data, $id, $sliderid){
        $link = (array)NextendParse::parse($data->get('link', ''));
        if(!isset($link[1])) $link[1] = '';
        
        $attr = '';
        $click = $data->get('onmouseclick', '');
        if(!empty($click)) $attr.= ' data-click="'.htmlspecialchars($click).'"';
        $enter = $data->get('onmouseenter', '');
        if(!empty($enter)) $attr.= ' data-enter="'.htmlspecialchars($enter).'"';
        $leave = $data->get('onmouseleave', '');
        if(!empty($leave)) $attr.= ' data-leave="'.htmlspecialchars($leave).'"';
        
        return 
'<div class="nextend-smartslider-button-'.$data->get('buttonclass', '').'-container '.$data->get('fontclass', '').'" style="cursor:pointer; width: 100%;" '.$attr.'>
    <a href="'.$link[0].'" '.($link[0] == '#' ? 'onclick="return false;" ' : '').'target="'.$link[1].'" style="display: block;" class="nextend-smartslider-button-'.$data->get('buttonclass', '').' '.$data->get('class', '').'">
        '.$data->get('content', '').'
    </a>
</div>
<style type="text/css">
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').'{
        '.$data->get('css', '').'
    }
    
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').':HOVER,
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').':FOCUS,
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').':ACTIVE{
        '.$data->get('csshover', '').'
    }
</style>';
    }
    
    function _renderAdmin($data, $id, $sliderid){
        $link = (array)NextendParse::parse($data->get('link', ''));
        if(!isset($link[1])) $link[1] = '';
        
        return 
'<div class="nextend-smartslider-button-'.$data->get('buttonclass', '').'-container '.$data->get('fontclass', '').'" style="cursor:pointer; width: 100%;">
    <a href="'.$link[0].'" '.($link[0] == '#' ? 'onclick="return false;" ' : '').' style="display: block;" class="nextend-smartslider-button-'.$data->get('buttonclass', '').' '.$data->get('class', '').'">
        '.$data->get('content', '').'
    </a>
</div>
<style type="text/css">
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').'{
        '.$data->get('css', '').'
    }
    
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').':HOVER,
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').':FOCUS,
    div#nextend-smart-slider-0 div.nextend-smartslider-button-'.$data->get('buttonclass', '').'-container a.nextend-smartslider-button-'.$data->get('buttonclass', '').':ACTIVE{
        '.$data->get('csshover', '').'
    }
</style>';
    }
    
    function getValues(){
        return array(    
            'content' => NextendText::_('Button'),     
            'link' => '#|*|_self', 
            'buttonclass' => 'blue-transition-rounded-button',
            'css' => "padding: 8px 10px;\nbox-shadow: 0 1px 1px RGBA(0,0,0,0.2);\ntext-transform: uppercase;\n-webkit-border-radius: 2px;\n-moz-border-radius: 2px;\nborder-radius: 2px;\nbackground: #2381e2;\n-webkit-transition: all 0.4s ease-out 0s;\n-moz-transition: all 0.4s ease-out 0s;\n-ms-transition: all 0.4s ease-out 0s;\n-o-transition: all 0.4s ease-out 0s;\ntransition: all 0.4s ease-out 0s;",
            'csshover' => "background: #1e70c5;\n-webkit-border-radius: 25px;\n-moz-border-radius: 25px;\nborder-radius: 25px;",
            'fontclass' => 'sliderfont11',
            'class' => '',
            'onmouseclick' => '',
            'onmouseenter' => '',
            'onmouseleave' => ''
        );
    }
    
    function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.$this->_identifier.DIRECTORY_SEPARATOR;
    } 
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemButton');