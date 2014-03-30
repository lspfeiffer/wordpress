<?php

nextendimportsmartslider2('nextend.smartslider.plugin.widget');
nextendimport('nextend.image.color');

class plgNextendSliderWidgetArrowTransition extends plgNextendSliderWidgetAbstract {

    var $_name = 'transition';

    function onNextendarrowList(&$list) {
        $list[$this->_name] = $this->getPath();
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'transition' . DIRECTORY_SEPARATOR;
    }

    static function render($slider, $id, $params) {

        $html = '';

        $previous = $params->get('previous', false);

        $next = $params->get('next', false);

        $enabled = ($previous && $previous != -1) || ($next && $next != -1);

        if ($enabled) {
            
            $displayclass = self::getDisplayClass($params->get('widgetarrowdisplay', '0|*|always|*|0|*|0'), true);

            list($colorhex, $rgbacss) = NextendColor::colorToCss($params->get('arrowbackground', '00ff00ff'));
            list($colorhexhover, $rgbacsshover) = NextendColor::colorToCss($params->get('arrowbackgroundhover', '000000ff'));
            
            $css = NextendCss::getInstance();
            $css->addCssFile(NextendFilesystem::translateToMediaPath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'transition' . DIRECTORY_SEPARATOR . 'style.css'));

            if ($previous && $previous != -1) {
                
                list($style, $data) = self::getPosition($params->get('previousposition', ''));
                
                $info = pathinfo($previous);
                $class = 'nextend-arrow-previous nextend-transition nextend-transition-previous nextend-transition-previous-' . basename($previous, '.' . $info['extension']);
                $html .= '<div onclick="njQuery(\'#' . $id . '\').smartslider(\'previous\');" class="' . $displayclass . $class . '" style="' . $style . '" '.$data.'><div class="smartslider-outer"></div><div class="smartslider-inner"></div></div>';
            }

            if ($next && $next != -1) {
                
                list($style, $data) = self::getPosition($params->get('nextposition', ''));
                
                $info = pathinfo($next);
                $class = 'nextend-arrow-next nextend-transition nextend-transition-next nextend-transition-next-' . basename($next, '.' . $info['extension']);
                $html .= '<div onclick="njQuery(\'#' . $id . '\').smartslider(\'next\');" class="' . $displayclass . $class . '" style="' . $style . '" '.$data.'><div class="smartslider-outer"></div><div class="smartslider-inner"></div></div>';
            }
            
            $css->addCssFile('
                #'.$id.' .nextend-transition.nextend-transition-previous .smartslider-outer,
                #'.$id.' .nextend-transition.nextend-transition-next .smartslider-outer{
                    background-color:' . $rgbacss . ';
                }
                #'.$id.' .nextend-transition.nextend-transition-previous .smartslider-inner,
                #'.$id.' .nextend-transition.nextend-transition-next .smartslider-inner{
                    background-color:' . $rgbacsshover . ';
                }', $id);
        }

        return $html;
    }

}
NextendPlugin::addPlugin('nextendsliderwidgetarrow', 'plgNextendSliderWidgetArrowTransition');