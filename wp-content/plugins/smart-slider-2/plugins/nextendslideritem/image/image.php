<?php

nextendimportsmartslider2('nextend.smartslider.plugin.slideritem');

class plgNextendSliderItemImage extends plgNextendSliderItemAbstract {

    var $_identifier = 'image';

    var $_title = 'Image';

    function getTemplate() {
        return '<div>
          <a href="{url}" style="display: block;background: none !important;" >
              <img src="{image}" style="display: block; max-width: 100%; {css};width:{width};height:{height};" class="{kenburnsclass}" />
              <style>
              .haskenburns{
                  width: 100% !important;
              }
              </style>
          </a>
        </div>';
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
        
        
        $size = (array)NextendParse::parse($data->get('size', ''));
        if(!isset($size[0])) $size[0] = 'auto';
        if(!isset($size[1])) $size[1] = 'auto';
        
        $kenburns = (array)NextendParse::parse($data->get('kenburns', ''));
        
        $style = '';
        
        if(isset($kenburns[0]) && $kenburns[0]){
            
            $size[0] = '100';
            $size[1] = 'auto';
            
            $duration = intval($kenburns[1])/1000;
            $targetWidth = intval($kenburns[2]);
            $snap = explode('-', $kenburns[3]);
            $delay = isset($kenburns[4]) ? intval($kenburns[4])/1000 : 0;
            
            $r = $targetWidth/100;
            
            $snapstyle = '';
            switch($snap[0]){
                case 'lt':
                    $snapstyle='position:absolute;left:0;top:0;right:auto;bottom:auto;';
                    break;
                case 'rt':
                    $snapstyle='position:absolute;left:auto;top:0;right:0;bottom:auto;';
                    break;
                case 'lb':
                    $snapstyle='position:absolute;left:0;top:auto;right:auto;bottom:0;';
                    break;
                case 'rb':
                    $snapstyle='position:absolute;left:auto;top:auto;right:0;bottom:0;';
                    break;
            }
            
            $style = '
            <style type="text/css">
                #nextend-smart-slider-0 #'.$id.'{
                    width:100%;
                    max-width:none !important;
                    -webkit-transition: -webkit-transform '.$duration.'s '.$delay.'s;
                    -moz-transition: -moz-transform '.$duration.'s '.$delay.'s;
                    -o-transition: -o-transform '.$duration.'s '.$delay.'s;
                    -ms-transition: -ms-transform '.$duration.'s '.$delay.'s;
                    transition: transform '.$duration.'s '.$delay.'s;
                    -moz-transform-origin:'.$snap[1].';
                    -ms-transform-origin:'.$snap[1].';
                    -webkit-transform-origin:'.$snap[1].';
                    transform-origin:'.$snap[1].';
                    '.$snapstyle.'
                }
                
                .x-ready #nextend-smart-slider-0.nextend-loaded .smart-slider-slide-active #'.$id.'{ 
                    width: '.$targetWidth.'%; 
                }
                
                .nextend-csstransforms.x-ready #nextend-smart-slider-0.nextend-loaded .smart-slider-slide-active #'.$id.'{
                    width:100%; 
                    -moz-transform: scale('.$r.','.$r.');
                    -ms-transform: scale('.$r.','.$r.');
                    -webkit-transform: scale('.$r.','.$r.');
                    transform: scale('.$r.','.$r.');
                }
            </style>
            ';
        }
        return $style.'<div '.$attr.'>
            '.($link[0] != '#' ? '<a href="'.$link[0].'" target="'.$link[1].'" style="display: block;background: none !important;">' : '').'
                <img id="'.$id.'" src="'.$data->get('image', '').'" style="display: block; max-width: 100%; '.htmlspecialchars($data->get('css', '')).';width:'.$size[0].';height:'.$size[1].';" class="'.$data->get('kenburnsclass', '').'" alt="'.htmlspecialchars($data->get('alt', '')).'" title="'.htmlspecialchars($data->get('title', '')).'" />
            '.($link[0] != '#' ? '</a>' : '').'
        </div>';
    }
    
    function _renderAdmin($data, $id, $sliderid){
    
        $link = (array)NextendParse::parse($data->get('link', ''));
        if(!isset($link[1])) $link[1] = '';
        
        $attr = '';
        $click = $data->get('onmouseclick', '');
        if(!empty($click)) $attr = ' data-click="'.htmlspecialchars($click).'"';
        $enter = $data->get('onmouseenter', '');
        if(!empty($enter)) $attr = ' data-enter="'.htmlspecialchars($enter).'"';
        $leave = $data->get('onmouseleave', '');
        if(!empty($leave)) $attr = ' data-leave="'.htmlspecialchars($leave).'"';
        
        
        $size = (array)NextendParse::parse($data->get('size', ''));
        if(!isset($size[0])) $size[0] = 'auto';
        if(!isset($size[1])) $size[1] = 'auto';
        
        $kenburns = (array)NextendParse::parse($data->get('kenburns', ''));
        
        $style = '';
        
        if(isset($kenburns[0]) && $kenburns[0]){
            $data->set('kenburnsclass','haskenburns');
        }
        
        return $style.'<div '.$attr.'>
            '.($link[0] != '#' ? '<a href="'.$link[0].'" style="display: block;background: none !important;">' : '').'
                <img id="'.$id.'" src="'.$data->get('image', '').'" style="display: block; max-width: 100%; '.htmlspecialchars($data->get('css', '')).';width:'.$size[0].';height:'.$size[1].';" class="'.$data->get('kenburnsclass', '').'" />
            '.($link[0] != '#' ? '</a>' : '').'
        </div>';
    }

    function getValues() {
        return array(
            'image' => NextendSmartSliderSettings::get('placeholder'),
            'alt' => NextendText::_('Image_not_available'),
            'title' => '',
            'link' => '#|*|_self',
            'size' => '100%|*|auto',
            'css' => '',
            'onmouseenter' => '',
            'onmouseclick' => '',
            'onmouseleave' => '',
            'kenburns' => '0|*|10000|*|115|*|0-50% 50%|*|0'
        );
    }

    function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->_identifier . DIRECTORY_SEPARATOR;
    }
}

NextendPlugin::addPlugin('nextendslideritem', 'plgNextendSliderItemImage');