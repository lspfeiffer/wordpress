<?php

nextendimportsmartslider2('nextend.smartslider.settings');
nextendimportsmartslider2('nextend.smartslider.widgets');
nextendimportsmartslider2('nextend.smartslider.items');

class NextendSlider {

    var $_norender = false;
    var $_path;
    var $_backend;
    var $_sliderid;
    var $_identifier = 'nextend-smart-slider';
    var $_instance;
    var $_typePath;
    var $_slider;
    var $_sliderParams;
    var $_generator;
    var $_generatorParams;
    var $_generatorSlideParams;
    var $_slides;
    var $_activeSlide = 0;
    
    var $_responsive = true;
    
    var $_replaceSlider = false;

    function NextendSlider($path, $backend = false) {

        $this->_path = $path . DIRECTORY_SEPARATOR;
        $this->_backend = $backend;
    }

    function getId() {
        return $this->_identifier . '-' . $this->_instance;
    }
        
    function setInstance() {
        if($this->_backend){
            $this->_instance = 0;
        }else{
            $this->_instance = $this->_sliderid;
        }
    }
    
    function preRender(){
        $this->loadSlider($this->_sliderid);
    }

    function loadSlider($sliderid) {
        nextendimportsmartslider2('nextend.smartslider.admin.models.sliders');
        
        if($this->_replaceSlider){
            $slider = $this->_replaceSlider;
        }else{
            $slidersModel = new NextendSmartsliderAdminModelSliders(null);
            $slider = $slidersModel->getSlider($sliderid);
            $this->_slider = new NextendData();
            $this->_slider->loadArray($slider);
    
            $this->_sliderParams = new NextendData();
            $this->_sliderParams->loadJSON($slider['params']);
    
            $this->_generatorParams = new NextendData();
            $this->_generatorParams->loadJSON($slider['generator']);
    
            $this->_generatorSlideParams = new NextendData();
            $this->_generatorSlideParams->loadJSON($slider['slide']);
        }

        $this->_slides = $this->slides();

        if (!$this->_backend) {
            if($this->_sliderParams->get('randomize', 0)){
                shuffle($this->_slides);
                $this->_activeSlide = 0;
            }
        }
        if (count($this->_slides) === 0) {
            if (NextendSmartSliderSettings::get('debugmessages', 1))
                echo '0 slides available for this slider.';
            $this->_norender = true;
        }
    }

    function slides() {
        return $this->initSlides($this->loadSlides());
    }

    function loadSlides() {
        $slides = array();
        $generateslides = NextendParse::parse($this->_generatorParams->get('generateslides', '0|*|0|*|0'));
        $createslides = intval($generateslides[1]);
        $staticslides = intval($generateslides[2]);
        
        $publishbetween = (array)NextendParse::parse($this->_generatorSlideParams->get('publishdates', '|*|'));
        if(!isset($publishbetween[1])) $publishbetween[1] = '';

        if (!$this->_backend && $this->_generatorParams->get('enabled', 0) && $createslides == 0 && ($publishbetween[0] == '' || strtotime($publishbetween[0]) < time()) && ($publishbetween[1] == '' || strtotime($publishbetween[1]) > time())) {
            nextendimportsmartslider2('nextend.smartslider.generator');
            $this->generator = new NextendSmartsliderGenerator($this->_generatorParams, $this->_generatorSlideParams, $this->_sliderid);
            $slides = $this->generator->generateSlides($this->_sliderid);
        } else {
            nextendimportsmartslider2('nextend.smartslider.admin.models.slides');
            $slidesModel = new NextendSmartsliderAdminModelSlides(null);

            $where = '';
            $date = '';
        	if(nextendIsWordpress()){		    		    
        		$date = current_time( 'mysql');
        	}else{
        		$date = date('Y-m-d H:i:s');
        	}
            if ($this->_backend) {
                $where = " AND ((published = 1 AND (publish_up = '0000-00-00 00:00:00' OR publish_up < '".$date."') AND (publish_down = '0000-00-00 00:00:00' OR publish_down > '".$date."')) OR id = " . NextendRequest::getInt('slideid') . ") ";
            } else {                
                $where = " AND published = 1 AND (publish_up = '0000-00-00 00:00:00' OR publish_up < '".$date."') AND (publish_down = '0000-00-00 00:00:00' OR publish_down > '".$date."') ";
            }

            $slides = $slidesModel->getSlides($this->_sliderid, $where);
            if (!$this->_backend && $createslides && !$staticslides) {
                nextendimportsmartslider2('nextend.smartslider.generator');
                $this->generator = new NextendSmartsliderGenerator($this->_generatorParams, $this->_generatorSlideParams, $this->_sliderid);
                $source = $this->_generatorParams->get('source', '');
                if ($source) {
                    $this->generator->initDatasource($source);
                    for ($i = 0; $i < count($slides); $i++) {
                        if ($slides[$i]['generator'] > 0) {
                            $slides[$i] = $this->generator->makeSlide($slides[$i]);
                        }
                    }
                }
            }
        }
        $this->_activeSlide = 0;
        return $slides;
    }

    function initSlides($slides) {
        if ($this->_backend) {
            if (NextendRequest::getCmd('action') == 'create') {
                array_unshift($slides, array(
                    'id' => 0,
                    'title' => '{_slidetitle}',
                    'slider' => NextendRequest::getInt('sliderid'),
                    'publish_up' => '0000-00-00 00:00:00',
                    'publish_down' => '0000-00-00 00:00:00',
                    'published' => 1,
                    'first' => 0,
                    'slide' => '',
                    'params' => '',
                    'thumbnail' => '',
                    'background' => 'ffffff00|*|',
                    'description' => 'Description',
                    'ordering' => count($slides)
                ));
            }

            if (NextendRequest::getCmd('action') == 'generatoredit') {
                global $smartslidergeneratorslide;
                if (!$smartslidergeneratorslide) $smartslidergeneratorslide = array();
                array_unshift($slides, array_merge(array(
                    'id' => 0,
                    'title' => '{_slidetitle}',
                    'slider' => NextendRequest::getInt('sliderid'),
                    'publish_up' => '0000-00-00 00:00:00',
                    'publish_down' => '0000-00-00 00:00:00',
                    'published' => 1,
                    'first' => 0,
                    'slide' => '',
                    'params' => '',
                    'thumbnail' => '',
                    'background' => 'ffffff00|*|',
                    'description' => 'Description',
                    'ordering' => count($slides)
                ), $smartslidergeneratorslide));
            } else if (NextendRequest::getCmd('controller') == 'layouts' && NextendRequest::getCmd('action') == 'edit') {
                global $smartsliderlayout;
                if (!$smartsliderlayout) $smartsliderlayout = '';
                array_unshift($slides, array(
                    'id' => 0,
                    'title' => '{_slidetitle}',
                    'slide' => $smartsliderlayout,
                    'params' => ''
                ));
            } else if (NextendRequest::getCmd('controller') == 'slides') {
                $currentlyedited = NextendRequest::getInt('slideid');
                for ($i = 0; $i < count($slides); $i++) {
                    if ($slides[$i]['id'] == $currentlyedited) {
                        $this->_activeSlide = $i;
                        break;
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($slides); $i++) {
                if ($slides[$i]['first'] == 1) {
                    $this->_activeSlide = $i;
                    break;
                }
            }
        }
        nextendimport('nextend.image.color');
        for ($i = 0; $i < count($slides); $i++) {
        
            $params = new NextendData();
            $params->loadJSON($slides[$i]['params']);
            $slides[$i]['params'] = $params;
            
            $slides[$i]['classes'] = 'smart-slider-canvas';
            if (!isset($slides[$i]['background'])) $slides[$i]['background'] = 'ffffff00|*|';
            $bg = (array)NextendParse::parse($slides[$i]['background']);
            $style = '';
            if (isset($bg[0]) && strlen($bg[0]) == 8) {
                if (substr($bg[0], 6, 2) != '00') {
                    $style .= 'background-color: #' . substr($bg[0], 0, 6) . ';';
                    $rgba = NextendColor::hex2rgba($bg[0]);
                    $style .= 'background-color: RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ');';
                }
            }
            $slides[$i]['style'] = $style;
            $slides[$i]['bg'] = false;
            if (isset($bg[1]) && $bg[1] != '') {
                    $slides[$i]['bg'] = $bg[1];
            }
            
            $link = $params->get('link', '');
            $link = (array)NextendParse::parse($link);
            if(!$this->_backend && $link && isset($link[0]) && $link[0] != '' && $link[0] != '#'){
                if(!isset($link[1])) $link[1] = '_self';
                $slides[$i]['link'] = ' onclick="'.htmlspecialchars(
                    strpos($link[0], 'javascript:') === 0 ? 
                        $link[0] : 
                        $link[1] == '_blank' ? "window.open('".(nextendIsJoomla() ? JRoute::_($link[0], false) : $link[0])."','_blank');" : "window.location='".(nextendIsJoomla() ? JRoute::_($link[0], false) : $link[0])."'"
                ).'" ';
                $slides[$i]['style'].='cursor:pointer;';
            }else{
                $slides[$i]['link'] = '';
            }
            
        }

        return $slides;
    }

    function render($cache = false) {
        $this->preRender();
        if ($this->_norender) return;

        $id = $this->getId();
        $data = & $this->_data;

        $this->addJs();

        $jquery = NextendSmartSliderSettings::get('jquery', 1);
        if(!$jquery && !class_exists('NextendSmartsliderAdminController', false)){
            $js = NextendJavascript::getInstance();
            $js->_loadedLibraries['jquery']->removeJsLibraryFile('jQuery.js');
        }
        
        if (isset($this->_slides[$this->_activeSlide]))
            $this->_slides[$this->_activeSlide]['classes'] .= ' smart-slider-slide-active';

        $fadeonload = (array)NextendParse::parse($this->_sliderParams->get('fadeonload', '1|*|0'));
        if(!isset($fadeonload[1])){
            $fadeonload[1] = 0;
        }else if($fadeonload[1]){
            $fadeonload[0] = 1;
        }
                
        $sliderClasses = (!$this->_backend && $fadeonload[0] ? 'nextend-slider-fadeload ' : '');
        $sliderClasses.= 'nextend-desktop ';
        
        $properties = $this->generateJSProperties();
        
        $fontsize = (array)NextendParse::parse($this->_sliderParams->get('globalfontsize', '12|*|11|*|10'));
        if(!isset($fontsize[1])) $fontsize[1] = 11;
        if(intval($fontsize[1])<=0) $fontsize[1] = $fontsize[0];
        if(!isset($fontsize[2])) $fontsize[2] = 10;
        if(intval($fontsize[2])<=0) $fontsize[2] = $fontsize[1];
        
        
        $size = $this->addCSS();
        
        $backgroundresize = $this->_sliderParams->get('backgroundresize', 0);
        if($backgroundresize != '0'){
            $works = nextend_try_to_test_memory_limit();
            if($works){
                $canvasWidth = $size[4];
                $canvasHeight = $size[5];
                $resizeremote = intval(NextendSmartSliderSettings::get('resizeremote', 0));
                nextendimport('nextend.cache.image');
                $imageCache = new NextendCacheImage();
                for($i = 0; $i < count($this->_slides); $i++){
                    $imageCache->setBackground(substr($this->_slides[$i]['background'],0,6));
                    $this->_slides[$i]['bg'] = $imageCache->resizeImage($this->_slides[$i]['bg'], $canvasWidth, $canvasHeight, $backgroundresize, $resizeremote);
                }
            }else if(NextendSmartSliderSettings::get('debugmessages', 1)){
                echo "It seems like the <a href='http://php.net/manual/en/ini.core.php#ini.memory-limit'>memory_limit</a> on the server is too low for the background resize. Please set it minimum 60M and reload the page! You can disable this message in <a href='http://www.nextendweb.com/wiki/smart-slider-documentation/global-settings/'>global configuration</a> 'Frontend debug message' option.";
            }
        }
        
        $css = NextendCss::getInstance();
        
        $widgets = new NextendSliderWidgets($this, $id);
        
        ob_start();
        if(!$this->_backend && $fadeonload[0]){
            $css->addCssFile('div#'.$id.'.nextend-slider-fadeload{position: absolute; opacity: 0;}', $this->getId());
        }
        include($this->_typePath . 'slider.php');
        $slider = ob_get_clean();
        
        $items = new NextendSliderItems($id, $this->_backend);
        $slider = $items->render($slider);
        
        if(!$this->_backend){
            NextendPlugin::callPlugin('nextendslideritem', 'onNextendSliderRender', array(&$slider, $id));
            
            $slider = preg_replace( '/data-itemvalues=".*?"/', '', $slider ); // Remove unnecessary attributes...
            $slider = preg_replace( '/data-item=".*?"/', '', $slider ); // Remove unnecessary attributes...
            $slider = preg_replace( '/data-leave=""/', '', $slider ); // Remove unnecessary attributes...
            $slider = preg_replace( '/data-enter=""/', '', $slider ); // Remove unnecessary attributes...
            $slider = preg_replace( '/data-click=""/', '', $slider ); // Remove unnecessary attributes...
        
            if(nextendIsJoomla()){
                if(version_compare(JVERSION, '1.6.0', 'ge')){
                    $dispatcher = JDispatcher::getInstance();
          			     JPluginHelper::importPlugin('content');
                    $article = new stdClass();
                    $article->text = $slider;
                    $_p = array();
                    $dispatcher->trigger('onContentPrepare', array('com_smartslider2', &$article, &$_p, 0));
                    if(!empty($article->text)) $slider = $article->text;
                }
            }elseif(nextendIsWordPress()){
                if(!function_exists('ss2_attr_shortcode')){
                    function ss2_attr_shortcode($matches){
                        return 'data-'.$matches[1].'="'.str_replace('"', '&quot;',do_shortcode(str_replace('&quot;','"',$matches[2]))).'"';
                    }
                }
                $slider = preg_replace_callback("/data-(click|enter|leave)=\"(.*?)\"/", "ss2_attr_shortcode", $slider);
                $slider = do_shortcode($slider);
            }
        }

        $slider = str_replace(array('{{id}}', 'nextend-smart-slider-0'), $this->getId(), $slider);

        if(!$this->_backend){
            $translateurl = (array)NextendParse::parse(NextendSmartSliderSettings::get('translateurl', ''));
            if(isset($translateurl[0]) && isset($translateurl[1])){
                $slider = str_replace($translateurl[0], $translateurl[1], $slider);
            }
        }
        
        echo $this->parseSlider($slider);
        $responsive = (array)NextendParse::parse($this->_sliderParams->get('responsive', '0|*|0'));
        
        if( !$this->_backend && $fadeonload[0] && ((isset($responsive[0]) && $responsive[0]) || (isset($responsive[1]) && $responsive[1]))){
            $works = nextend_try_to_test_memory_limit();
            if($works){
                if($size[0]+$size[3] > 0 && $size[1] > 0 && function_exists('imagecreatetruecolor')){
                    echo '<div id="'.$id.'-placeholder" >';
                    
                    $im = imagecreatetruecolor($size[0]+$size[3], $size[1]);
                    imagesavealpha($im, true);
                    imagealphablending($im, false);
                    $trans = imagecolorallocatealpha($im, 255, 0, 0, 127);
                    imagefilledrectangle($im, 0, 0, $size[0]+$size[3], $size[1], $trans);
                    ob_start();
                    imagepng($im);
                    imagedestroy($im);
                    $img = base64_encode(ob_get_clean());
                    echo '<img alt="" style="width:100%; max-width: '.(intval($this->_sliderParams->get('simpleresponsivemaxwidth', 30000))+$size[3]).'px;" src="data:image/png;base64,'.$img.'" />';
                    
                    if($size[2] > 0){
                        $im = imagecreatetruecolor($size[0]+$size[3], $size[2]);
                        imagesavealpha($im, true);
                        imagealphablending($im, false);
                        $trans = imagecolorallocatealpha($im, 255, 0, 0, 127);
                        imagefilledrectangle($im, 0, 0, $size[0]+$size[3], $size[2], $trans);
                        ob_start();
                        imagepng($im);
                        imagedestroy($im);
                        $img = base64_encode(ob_get_clean());
                        echo '<img alt="" style="width:100%;" src="data:image/png;base64,'.$img.'" />';
                    }
                    
                    echo '</div>';
                }else{
                    $css->addCssFile('#'.$id.' .nextend-slider-fadeload{position: relative !important;}', $this->getId());
                }
            }else if(NextendSmartSliderSettings::get('debugmessages', 1)){
                echo "It seems like the <a href='http://php.net/manual/en/ini.core.php#ini.memory-limit'>memory_limit</a> on the server is too low for the fade on load feature. Please set it minimum 60M and reload the page! You can disable this message in <a href='http://www.nextendweb.com/wiki/smart-slider-documentation/global-settings/'>global configuration</a> 'Frontend debug message' option.";
            }
        }else{
            $css->addCssFile('#'.$id.'.nextend-slider-fadeload{position: relative !important;}', $this->getId());
        }
        
        NextendPlugin::callPlugin('nextendslider', 'onNextendSliderRenderAfter');
        
        if(!$cache){
            if(nextendIsWordPress()){
                add_action('nextend_css', array( $this, 'wpAddCSS'));
            }elseif(nextendIsMagento()){
                Nextend_SmartSlider2_Model_Observer::$sliders[] = $this->getId();
            }else{
                $css->generateCSS($this->getId());
            }
        }
    }
    
    function generateJSProperties(){
        $p = array();
        
        $p['translate3d'] = intval(NextendSmartSliderSettings::get('translate3d', 1));
        $p['playfirstlayer'] = intval($this->_sliderParams->get('playfirstlayer', 0));
        $p['mainafterout'] = intval($this->_sliderParams->get('mainafterout', 1));
        $p['inaftermain'] = intval($this->_sliderParams->get('inaftermain', 1));
        
        $fadeonload = (array)NextendParse::parse($this->_sliderParams->get('fadeonload', '1|*|0'));
        if(!isset($fadeonload[1])){
            $fadeonload[1] = 0;
        }else if($fadeonload[1]){
            $fadeonload[0] = 1;
        }
        $p['fadeonscroll'] = intval($fadeonload[1]);
        
        $count = count($this->_slides);
        
        $autoplay = NextendParse::parse($this->_sliderParams->get('autoplay', '0|*|5000'));
        $autoplayfinish = NextendParse::parse($this->_sliderParams->get('autoplayfinish', '0|*|loop|*|current'));
        $autoplayToSlide = 0;
        
        if(isset($autoplayfinish[1])){
            switch($autoplayfinish[1]){
                case 'slide':
                    if(isset($autoplayfinish[2]) && $autoplayfinish[2] == 'next'){
                        $autoplayToSlide = intval($autoplayfinish[0]) + 1;
                    }else{
                        $autoplayToSlide = intval($autoplayfinish[0]);
                    }
                    break;
                default: 
                    if(isset($autoplayfinish[2]) && $autoplayfinish[2] == 'next'){
                        $autoplayToSlide = intval($autoplayfinish[0]) * $count + 1;
                    }else{
                        $autoplayToSlide = intval($autoplayfinish[0]) * $count;
                    }
                    break;
            }
        }

        $stopautoplay = NextendParse::parse($this->_sliderParams->get('stopautoplay', '1|*|1|*|1'));
        $resumeautoplay = NextendParse::parse($this->_sliderParams->get('resumeautoplay', '0|*|1|*|0'));
        if(!isset($resumeautoplay[2])) $resumeautoplay[2] = 0;
        
        $p['autoplay'] =  intval($autoplay[0]);
        $p['autoplayConfig'] = array(
            'duration' => intval($autoplay[1]),
            'counter' => 0,
            'autoplayToSlide' => intval($autoplayToSlide),
            'stopautoplay' => array(
                'click' => intval($stopautoplay[0]),
                'mouseenter' => intval($stopautoplay[1]),
                'slideplaying' =>intval($stopautoplay[2])
            ),
            'resumeautoplay' => array(
                'mouseleave' => intval($resumeautoplay[0]),
                'slideplayed' => intval($resumeautoplay[1]),
                'slidechanged' => intval($resumeautoplay[2])
            )
        );

        $responsive = NextendParse::parse($this->_sliderParams->get('responsive', '0|*|0'));
        if(!$this->_responsive) $responsive = array(0, 0);
        
        $responsivescreenwidth = (array)NextendParse::parse(NextendSmartSliderSettings::get('responsivescreenwidth', '1024|*|640'));
        if($responsivescreenwidth[0] < $responsivescreenwidth[1]) $responsivescreenwidth[1] = 1;
        
        $slideeditorratios = array_map('floatval', (array)NextendParse::parse(NextendSmartSliderSettings::get('slideeditorratios', '1.0|*|1.0|*|0.7|*|0.5')));

        $p['responsive'] = array(
            'downscale' => intval($responsive[0]),
            'upscale' => intval($responsive[1]),
            'maxwidth' => intval($this->_sliderParams->get('simpleresponsivemaxwidth', 3000)),
            'basedon' => NextendSmartSliderSettings::get('responsivebasedon', 'combined'),
            'screenwidth' => array(
                'tablet' => intval($responsivescreenwidth[0]),
                'phone' => intval($responsivescreenwidth[1])
            ),
            'ratios' => $slideeditorratios
        );
        
        $controls = NextendParse::parse($this->_sliderParams->get('controls', '0|*|0|*|0'));
        if(!isset($controls[2])) $controls[2] = 0;
        $p['controls'] = array(
            'scroll' => intval($controls[0]),
            'touch' => $controls[1],
            'keyboard' => intval($controls[2])
        );
        $p['blockrightclick'] = intval($this->_sliderParams->get('blockrightclick', 0));
        
        return $p;
    }
    
    function parseSlider($slider){
        return $slider;
    }

    function addCSS() {
        $id = $this->getId();

        nextendimport('nextend.css.css');
        $css = NextendCss::getInstance();
        $css->createGroup($id);
        $css->enableLess($id);
        $css->addLessImportDir(NEXTEND_SMART_SLIDER2_ASSETS . 'less' . DIRECTORY_SEPARATOR, $id);
        $data = & $this->_data;
        $slider = & $this->_slider;
        $sliderParams = & $this->_sliderParams;
        $size = NextendParse::parse($sliderParams->get('size'));
        $context = array(
            'id' => '~"#' . $this->getId() . '"',
            'width' => $size[0] . 'px',
            'height' => $size[1] . 'px',
            'canvas' => intval($size[2]),
            'count' => count($this->_slides),
            'margin' => NextendParse::parseUnit($sliderParams->get('margin'), ' ')
        );
        
        if($this->_backend){
            $context['margin'] = '0px 0px 0px 0px';
        }

        nextendimport('nextend.parse.font');
        $fonts = 0;
        foreach (NextendSmartSliderFontSettings::getAll($this->_sliderid) AS $k => $v) {
            preg_match('/sliderfont(custom)?([0-9]*)$/', $k, $matches);
            if (count($matches)) {
                $context['font' . $fonts] = '~".' . $matches[0] . '"';
                
                $tmp = json_decode($v);
                if($tmp===null || $tmp == $v) $v = base64_decode($v);
                
                $font = new NextendParseFont($v);
                $context['font' . $fonts . 'text'] = '";' . $font->printTab() . '"';
                $font->mixinTab('Link');
                $context['font' . $fonts . 'link'] = '";' . $font->printTab('Link') . '"';
                $font->mixinTab('Link:Hover', 'Link');
                $context['font' . $fonts . 'hover'] = '";' . $font->printTab('Link:Hover') . '"';
                $fonts++;
            }
        }
        $context['fonts'] = $fonts;

        include($this->_typePath . 'context.php');
        $css->addCssFile(array(
            $this->getId(),
            $this->_typePath . 'style.less',
            $context
        ), $this->getId());
        
        if(strpos($context['margin'], '%')){
            $m = explode('% ', $context['margin']);
            $m[1] = $m[1]/100*intval($context['width']);
            $m[3] = $m[3]/100*intval($context['width']);
            $m[0] = $m[0]/100*intval($context['height']);
            $m[2] = $m[2]/100*intval($context['height']);
    		}else{
            $m = explode('px ', $context['margin']);
    		}
		
		    $addcss = (array)NextendParse::parse(NextendSmartSliderSettings::get('externalcssfile'));
		    if($this->_backend && count($addcss)){
      			foreach($addcss as $cssfile){
      				$css->addCssFile($cssfile);
      			}
		    }
        return array(intval($context['width']),intval($context['height']), $m[0]+$m[2], $m[1]+$m[3], intval($context['canvaswidth']), intval($context['canvasheight']));
    }
    
    function wpAddCSS(){
        $css = NextendCss::getInstance();
        $css->generateCSS($this->getId());
    }

    function addJs() {

        nextendimport('nextend.javascript.javascript');
        nextendimport('nextend.css.css');
        $data = & $this->_data;
        $js = NextendJavascript::getInstance();
        $css = NextendCss::getInstance();

        $js->loadLibrary('modernizr');
        $js->addJsAssetsFile('class.js');
        $js->loadLibrary('jquery');
        
        $js->addLibraryJsLibraryFile('jquery', 'jquery.waitforimages.js');

        $controls = NextendParse::parse($this->_sliderParams->get('controls', '0|*|0'));

        if($controls[0]){
            $js->addLibraryJsLibraryFile('jquery', 'jquery.mousewheel.js');
        }
        if($controls[1] || $this->_sliderParams->get('improvedtouch', 0)){
            $js->addLibraryJsLibraryFile('jquery', 'jquery.touchSwipe.js');
        }
        $js->addLibraryJsLibraryFile('jquery', 'easing.js');
        $js->addLibraryJsLibraryFile('jquery', 'jquery.transit.js');


        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'animationbase.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'smartsliderbase.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'mainslider.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'layers.js');

        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'motions' . DIRECTORY_SEPARATOR . 'no.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'motions' . DIRECTORY_SEPARATOR . 'fade.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'motions' . DIRECTORY_SEPARATOR . 'fadestatic.js');

        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'motions' . DIRECTORY_SEPARATOR . 'slide.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'js' . DIRECTORY_SEPARATOR . 'motions' . DIRECTORY_SEPARATOR . 'slidestatic.js');

        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS .  'js' . DIRECTORY_SEPARATOR . 'motions' . DIRECTORY_SEPARATOR . 'transit.js');
    }

}

function nextend_try_to_test_memory_limit(){
    $works = true;
    if(function_exists('ini_get')){
        $memory_limit = @ini_get('memory_limit');
        if($memory_limit && $memory_limit != ''){
            $ok = nextend_setting_to_bytes($memory_limit) >= 0x3C00000;
            if(!$ok) $works = false;
        }
    }
    return $works;
}

function nextend_setting_to_bytes($setting)
{
    static $short = array('k' => 0x400,
                          'm' => 0x100000,
                          'g' => 0x40000000);

    $setting = (string)$setting;
    if (!($len = strlen($setting))) return NULL;
    $last    = strtolower($setting[$len - 1]);
    $numeric = 0 + $setting;
    $numeric *= isset($short[$last]) ? $short[$last] : 1;
    return $numeric;
}