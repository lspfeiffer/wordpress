<?php

nextendimportsmartslider2('nextend.smartslider.plugin.widget');
nextendimport('nextend.image.color');

class plgNextendSliderWidgetBulletTransition extends plgNextendSliderWidgetAbstract {

    var $_name = 'transition';

    function onNextendbulletList(&$list) {
        $list[$this->_name] = $this->getPath();
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'transition' . DIRECTORY_SEPARATOR;
    }

    static function render($slider, $id, $params) {

        $html = '';

        $bullet = $params->get('bullet', false);
        if ($bullet && $bullet != -1) {

            $displayclass = self::getDisplayClass($params->get('widgetbulletdisplay', '0|*|always|*|0|*|0'), true).'nextend-widget-bullet ';

            $css = NextendCss::getInstance();
            $css->addCssFile(NextendFilesystem::translateToMediaPath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'transition' . DIRECTORY_SEPARATOR . 'style.css'));
            
            
            list($colorhex, $rgbacss) = NextendColor::colorToCss($params->get('bulletbackground', '00000060'));
            list($colorhexhover, $rgbacsshover) = NextendColor::colorToCss($params->get('bulletbackgroundhover', '7670C7ff'));
            list($colorhexborder, $rgbacssborder) = NextendColor::colorToCss($params->get('bulletborder', '00000060'));
            list($colorhexborderhover, $rgbacssborderhover) = NextendColor::colorToCss($params->get('bulletborderhover', '7670C7ff'));
            list($colorhexborderbar, $rgbacssborderbar) = NextendColor::colorToCss($params->get('bulletbarcolor', '00000060'));
            list($colorhexthumbnail, $rgbacssthumbnail) = NextendColor::colorToCss($params->get('bulletthubmnail', '00000060'));

            list($style, $data) = self::getPosition($params->get('bulletposition', ''));
            
            $style.= 'visibility: hidden;z-index:10; line-height: 0;';
            
            $width = NextendParse::parse($params->get('bulletwidth', 'width'));
            if(is_numeric($width) || $width == 'auto' || substr($width, -1) == '%'){
                $style.= 'width:'.$width.';';
            }else{
                $data.= 'data-sswidth="'.$width.'" ';
            }

            $bulletalign = $params->get('bulletalign', 'center');
            if ($bulletalign) {
                $style .= 'text-align:' . $bulletalign . ';';
            }

            $info = pathinfo($bullet);
            $class = 'nextend-bullet nextend-bullet-transition nextend-bullet-transition-' . basename($bullet, '.' . $info['extension']);
            
            $class.= ' nextend-bullet-'.$params->get('bulletorientation', 'horizontal');

            $shadow = $params->get('bulletshadow', 'none');
            switch ($shadow) {
                case 'inner':
                    $class .= ' bullet-shadow-inner';
                    break;
                case 'outer':
                    $class .= ' bullet-shadow-outer';
                    break;
            }

            $bar = $params->get('bulletbar', 'none');
            switch ($bar) {
                case 'simplerounded':
                    $class .= ' bullet-bar-simple-rounded';
                    break;
                case 'elegantrounded':
                    $class .= ' bullet-bar-elegant-rounded';
                    break;
                case 'simple':
                    $class .= ' bullet-bar-simple';
                    break;
                case 'elegant':
                    $class .= ' bullet-bar-elegant';
                    break;
            }


            $html .= '<div style="' . $style . '" class="' . $displayclass . '" '.$data.'><div class="nextend-bullet-container ' . $class . '">';
            $i = 0;
            foreach ($slider->_slides AS $slide) {
                $html .= '<div onclick="njQuery(\'#' . $id . '\').smartslider(\'goto\',' . $i . ',false);" data-thumbnail="' . $slide['thumbnail'] . '"  class="' . $class . ($slide['first'] ? ' active' : '') . '"></div>';
                $i++;
            }
            $html .= '</div></div>';

            $bullethumbnail = NextendParse::parse($params->get('bullethumbnail', false), '0|*|top');
            $thumbnailsize = NextendParse::parse($params->get('thumbnailsizebullet', false), '100|*|60');
            
            self::tooltip($id, $bullethumbnail, "nextend-bullet-transition-thumbnail");

            $css->addCssFile('
                #'.$id.' .nextend-bullet-container .nextend-bullet-transition.nextend-bullet{
                  background: #' . $colorhex . ';
                  background:' . $rgbacss . ';
                }
                #'.$id.' .nextend-bullet-container .nextend-bullet-transition.nextend-bullet.active,
                #'.$id.' .nextend-bullet-container .nextend-bullet-transition.nextend-bullet:HOVER{
                  background: #' . $colorhexhover . ';
                  background:' . $rgbacsshover . ';
                }              
                #'.$id.' .nextend-bullet-container .nextend-bullet-transition.nextend-bullet{
                  border-color: #' . $colorhexborder . ';
                  border-color:' . $rgbacssborder . ';
                }
                #'.$id.' .nextend-bullet-container .nextend-bullet-transition.nextend-bullet.active,
                #'.$id.' .nextend-bullet-container .nextend-bullet-transition.nextend-bullet:HOVER{
                  border-color: #' . $colorhexborderhover . ';
                  border-color:' . $rgbacssborderhover . ';
                }
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-transition.bullet-bar-simple-rounded,              
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-transition.bullet-bar-elegant-rounded,
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-transition.bullet-bar-simple,              
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-transition.bullet-bar-elegant{
                  background:#' . $colorhexborderbar . ';
                  background:' . $rgbacssborderbar . ';
                }
                #'.$id.' .nextend-bullet-transition-thumbnail .qtip-content{
                  width:' . $thumbnailsize[0] . 'px;
                  height:' . $thumbnailsize[1] . 'px;
                  padding: 4px;
                }         
                #'.$id.' .nextend-bullet-transition-thumbnail .qtip-content img{
                  box-shadow: 0 0px 0px 1px RGBA(255,255,255,.2);
                }
                #'.$id.' .nextend-bullet-transition-thumbnail{
                  background: #' . $colorhexthumbnail . ';
                  background: ' . $rgbacssthumbnail . ';
                }', $id);
        }

        return $html;
    }

}
NextendPlugin::addPlugin('nextendsliderwidgetbullet', 'plgNextendSliderWidgetBulletTransition');