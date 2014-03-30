<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemHeading extends plgNextendSliderItemAbstract {

    var $_identifier = 'heading';
    var $_title = 'Heading';

    function getTemplate() {
        return "
            <h{priority} class='{fontclass} {class}' style=\"{fontsizer}{fontcolorr}{css_esc}\">
                <a href='{url}' style='{fontcolorr}'>
                  {heading}
                </a>
            </h{priority}>
        ";
    }
    
    function _render($data, $id, $sliderid){
        $link = (array)NextendParse::parse($data->get('link', ''));
        if(!isset($link[1])) $link[1] = '';
        
        $fontsize = $data->get('fontsize', '');
        if(!empty($fontsize)) $fontsize = 'font-size:'.$fontsize.'%;';
        
        $fontcolors = (array)NextendParse::parse($data->get('fontcolor', ''));
        $fontcolor = '';
        if(isset($fontcolors[0]) && $fontcolors[0]){
            if(!empty($fontcolors[1])) $fontcolor = 'color:#'.$fontcolors[1].';';
        }
        
        $attr = '';
        $click = $data->get('onmouseclick', '');
        if(!empty($click)) $attr.= ' data-click="'.htmlspecialchars($click).'"';
        $enter = $data->get('onmouseenter', '');
        if(!empty($enter)) $attr.= ' data-enter="'.htmlspecialchars($enter).'"';
        $leave = $data->get('onmouseleave', '');
        if(!empty($leave)) $attr.= ' data-leave="'.htmlspecialchars($leave).'"';
        
        return '
            <h'.$data->get('priority', 1).' class="'.$data->get('fontclass', 'sliderfont2').' '.$data->get('class', '').'" style="'.$fontsize.$fontcolor.htmlspecialchars($data->get('css', '')).'" '.$attr.'>
                '.($link[0] != '#' ? '<a href="'.$link[0].'" target="'.$link[1].'" style="'.$fontcolor.'">' : '').'
                  '.($data->get('heading', '')).'
                '.($link[0] != '#' ? '</a>' : '').'
            </h'.$data->get('priority', 1).'>
        ';
    }
    
    function _renderAdmin($data, $id, $sliderid){
        $link = (array)NextendParse::parse($data->get('link', ''));
        if(!isset($link[1])) $link[1] = '';
        
        $fontsize = $data->get('fontsize', '');
        if(!empty($fontsize)) $fontsize = 'font-size:'.$fontsize.'%;';
        
        $fontcolors = (array)NextendParse::parse($data->get('fontcolor', ''));
        $fontcolor = '';
        if(isset($fontcolors[0]) && $fontcolors[0]){
            if(!empty($fontcolors[1])) $fontcolor = 'color:#'.$fontcolors[1].';';
        }
        
        return '
            <h'.$data->get('priority', 1).' class="'.$data->get('fontclass', 'sliderfont2').' '.$data->get('class', '').'" style="'.$fontsize.$fontcolor.htmlspecialchars($data->get('css', '')).'">
                '.($link[0] != '#' ? '<a href="'.$link[0].'" target="'.$link[1].'" style="'.$fontcolor.'">' : '').'
                  '.($data->get('heading', '')).'
                '.($link[0] != '#' ? '</a>' : '').'
            </h'.$data->get('priority', 1).'>
        ';
    }

    function getValues() {
        return array(
            'priority' => '1',
            'heading' =>  NextendText::_('Heading'),
            'link' => '#|*|_self',
            'fontclass' => 'sliderfont2',
            'fontsize' => 'auto',
            'fontcolor' => '0|*|000000',
            'css' => "padding: 0;\nmargin: 0;\nbackground: none;\nbox-shadow: none;",
            'class' => '',
            'onmouseenter' => '',
            'onmouseclick' => '',
            'onmouseleave' => '',
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }

}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemHeading');