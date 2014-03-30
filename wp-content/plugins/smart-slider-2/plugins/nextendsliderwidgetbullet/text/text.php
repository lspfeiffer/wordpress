<?php

nextendimportsmartslider2('nextend.smartslider.plugin.widget');
nextendimport('nextend.image.color');

class plgNextendSliderWidgetBulletText extends plgNextendSliderWidgetAbstract {

    var $_name = 'text';

    function onNextendbulletList(&$list) {
        $list[$this->_name] = $this->getPath();
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'text' . DIRECTORY_SEPARATOR;
    }

    static function render($slider, $id, $params) {

        $html = '';
        
        $bullet = $params->get('bullet', false);
        if ($bullet && $bullet != -1) {

            $displayclass = self::getDisplayClass($params->get('widgetbulletdisplay', '0|*|always|*|0|*|0'), true).'nextend-widget-bullet ';

            $css = NextendCss::getInstance();
            $css->addCssFile(NextendFilesystem::translateToMediaPath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'text' . DIRECTORY_SEPARATOR . 'style.css'));

            list($colorhex, $rgbacss) = NextendColor::colorToCss($params->get('bulletbackground', '00000060'));
            list($colorhexhover, $rgbacsshover) = NextendColor::colorToCss($params->get('bulletbackgroundhover', '7670C7ff'));
            list($colorhexborderbar, $rgbacssborderbar) = NextendColor::colorToCss($params->get('bulletbarcolor', '00000060'));
            list($colorhexthumbnail, $rgbacssthumbnail) = NextendColor::colorToCss($params->get('bulletthumbnail', '00000060'));
            
            list($style, $data) = self::getPosition($params->get('bulletposition', ''));
            
            $style.= 'visibility: hidden;z-index:10;line-height: 0;';
            
            $width = NextendParse::parse($params->get('bulletwidth', 'width'));
            if(is_numeric($width) || $width == 'auto' || substr($width, -1) == '%'){
                $style.= 'width:'.$width.';';
            }else{
                $data.= 'data-sswidth="'.$width.'" ';
            }

            $bulletalign = $params->get('bulletalign', 'center');
            if ($bulletalign) {
                $style.= 'text-align:'.$bulletalign.';';
            }

            $info = pathinfo($bullet);
            $class = 'nextend-bullet nextend-bullet-text nextend-bullet-text-' . basename($bullet, '.' . $info['extension']);
            
            $class.= ' nextend-bullet-'.$params->get('bulletorientation', 'horizontal');
        
            $shadow = $params->get('bulletshadow', 'none');
            switch($shadow){
              case 'inner':                
                $class.=' bullet-shadow-inner';
                break;
              case 'outer':                
                $class.=' bullet-shadow-outer';
                break;
            }   

            $bar = $params->get('bulletbar', 'none');
            switch($bar){
              case 'simplerounded':                
                $class.=' bullet-bar-simple-rounded';
                break;
              case 'elegantrounded':                
                $class.=' bullet-bar-elegant-rounded';
                break;
              case 'simple':                
                $class.=' bullet-bar-simple';
                break;
              case 'elegant':                
                $class.=' bullet-bar-elegant';
                break;
            }

            $html .= '<div style="' . $style . '" class="'.$displayclass.'" '.$data.'><div class="nextend-bullet-container ' . $class . '">';
            $i = 0;
            foreach ($slider->_slides AS $slide) {
                $html .= '<div onclick="njQuery(\'#'.$id.'\').smartslider(\'goto\','.$i.',false);" data-thumbnail="'.$slide['thumbnail'].'"  class="' . $class . ($slide['first'] ? ' active' : ''). '"><span class="'.$params->get('fontclassnumber', 'sliderfont7').'">
                ' .$slide['title']. '
                </span></div>';
                $i++;
            }
            $html .= '</div></div>';
            
            self::tooltip($id, NextendParse::parse($params->get('bullethumbnail', false), '0|*|top'), "nextend-bullet-text-thumbnail");
            
            $thumbnailsize = NextendParse::parse($params->get('thumbnailsizebullet', false), '100|*|60');
            
            $css->addCssFile('
                #'.$id.' .nextend-bullet-container .nextend-bullet-text.nextend-bullet{                
                  background:'.$rgbacss.';
                }
                #'.$id.' .nextend-bullet-container .nextend-bullet-text.nextend-bullet.active,
                #'.$id.' .nextend-bullet-container .nextend-bullet-text.nextend-bullet:HOVER{
                  background:'.$rgbacsshover.';
                }
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-text.bullet-bar-simple-rounded,              
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-text.bullet-bar-elegant-rounded,
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-text.bullet-bar-simple,              
                #'.$id.' .nextend-bullet-container.nextend-bullet.nextend-bullet-text.bullet-bar-elegant{              
                  background:'.$rgbacssborderbar.';
                }
                #'.$id.' .nextend-bullet-text-thumbnail .qtip-content{
                  width:'.$thumbnailsize[0].'px;                
                  height:'.$thumbnailsize[1].'px;
                  padding: 4px;
                }         
                #'.$id.' .nextend-bullet-text-thumbnail .qtip-content img{
                  box-shadow: 0 0px 0px 1px RGBA(255,255,255,.2);
                }
                #'.$id.' .nextend-bullet-text-thumbnail{
                  background: '.$rgbacssthumbnail.';
                }', $id);
        }

        return $html;
    }

}
NextendPlugin::addPlugin('nextendsliderwidgetbullet', 'plgNextendSliderWidgetBulletText');