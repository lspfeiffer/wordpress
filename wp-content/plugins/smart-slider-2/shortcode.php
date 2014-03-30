<?php

function smart_slider2_shortcode($atts) {
    extract(shortcode_atts(array(
        'id' => md5(time()),
        'slider' => 0,
        'tablet' => -1,
        'phone' => -1
      ), $atts));
    
    $loadCheck = false;
    if($tablet >= 0 || $phone >= 0) $loadCheck = true;
    
    if($loadCheck){
        nextendimport('nextend.externals.mobiledetect');
        $detect = new Mobile_Detect();
        $istablet = $detect->isTablet();
        $ismobile = !$istablet && $detect->isMobile();
        
        if($istablet){
            if($tablet == 0){
                return '';
            }
            if($tablet > 0) $slider = intval($tablet);
        }
        if($ismobile){
            if($phone == 0){
                return '';
            }
            if($phone > 0) $slider = intval($phone);
        }
        
    }

    if ($slider == 0)
        return '';

    $params = array();
    
    nextendimportsmartslider2('nextend.smartslider.slidercache');
    nextendimportsmartslider2('nextend.smartslider.wordpress.slider');
    
    ob_start();
    
    new NextendSliderCache(new NextendSliderWordpress(intval($slider), $params, dirname(__FILE__)));
    return ob_get_clean();
}

add_shortcode('smartslider2', 'smart_slider2_shortcode');
