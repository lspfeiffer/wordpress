<?php

nextendimport('nextend.parse.parse');

class plgNextendSliderWidgetAbstract extends NextendPluginBase {
    
    static function getDisplayClass($value, $hasHover){
        $display = NextendParse::parse($value);
        if(!isset($display[2]) || $display[2] == '') $display[2] = $display[0];
        if(!isset($display[3]) || $display[3] == '') $display[3] = $display[0];
    
        $displayclass = 'nextend-widget ';
        if($hasHover) $displayclass.= 'nextend-widget-' . $display[1] . ' '; 
        if($display[0]) $displayclass.= 'nextend-widget-display-desktop ';
        if($display[2]) $displayclass.= 'nextend-widget-display-tablet ';
        if($display[3]) $displayclass.= 'nextend-widget-display-phone ';
        return $displayclass;
    }
    
    static function getPosition($value){
        $data = '';
        $style = 'position: absolute;';
        $position = NextendParse::parse($value);

        if (count($position)) {
            if(!is_numeric($position[1])){
                $data.= 'data-ss'.$position[0].'="'.$position[1].'" ';
            }else{
                $style .= $position[0] . ':' . $position[1] . $position[2] . ';';
            }
            
            if(!is_numeric($position[4])){
                $data.= 'data-ss'.$position[3].'="'.$position[4].'" ';
            }else{
                $style .= $position[3] . ':' . $position[4] . $position[5] . ';';
            }
        }
        return array($style, $data);
    }
    
    static function tooltip($id, $bullethumbnail, $classes){
        if ($bullethumbnail[0]) {
            $css = NextendCss::getInstance();
            $js = NextendJavascript::getInstance();
            $css->addCssLibraryFile('jquery.qtip.min.css');
            $js->loadLibrary('jquery');
            $js->addLibraryJsAssetsFile('jquery', 'jquery.qtip.min.js');
            $my = '';
            $at = '';
            $y = 0;
            $x = 0;
            switch ($bullethumbnail[1]) {
                case 'right':
                    $my = 'left center';
                    $at = 'right center';
                    $x = 3;
                    break;
                case 'bottom':
                    $my = 'top center';
                    $at = 'bottom center';
                    $y = 3;
                    break;
                case 'left':
                    $my = 'right center';
                    $at = 'left center';
                    $x = -3;
                    break;
                default:
                    $my = 'bottom center';
                    $at = 'top center';
                    $y = -3;
            }

            $js->addLibraryJs('jquery', '$("#' . $id . ' .nextend-bullet-container .nextend-bullet:not([data-thumbnail=\"\"])").qtip({
                    position: {
                        my: "' . $my . '",
                        at: "' . $at . '",
                        adjust: {
                          x: ' . $x . ',
                          y: ' . $y . '
                        },
                        container: $("#'.$id.'")
                    },
                    prerender: true,
                    style: {
                        tip: {
                            width: 14,
                            height: 6
                        },
                        classes: "'.$classes.'"
                    },
                    content: {
                        text: function(e, api) {
                            var img = $(this).attr("data-thumbnail");
                            return "<img src=\'" + img + "\' style=\'width:100%;\' />";
                        }
                    }
                });
            ');
        }
    }
 
}