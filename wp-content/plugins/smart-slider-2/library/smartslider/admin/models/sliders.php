<?php

nextendimportsmartslider2('nextend.smartslider.admin.models.base');
nextendimport('nextend.parse.parse');

class NextendSmartsliderAdminModelSliders extends NextendSmartsliderAdminModelBase {

    function getSlider($id) {
        if ($id === -1) return $this->getLayoutSlider();
        $db = NextendDatabase::getInstance();
        $db->setQuery('SELECT * FROM #__nextend_smartslider_sliders WHERE id = ' . $db->quote($id));
        return $db->loadAssoc();
    }

    function getLayoutSlider() {
        return array(
            'id' => -1,
            'title' => 'Test',
            'type' => 'simple',
            'generator' => null,
            'slide' => null,
            'params' => '{"size":"' . NextendSmartSliderLayoutSettings::get('size', '700|*|300') . '|*|1","responsive":"0|*|0","globalfontsize":"14","margin":"0|*|0|*|0|*|0|*|px","accordionhorizontalskins":"","accordionhorizontalborder":"6|*|3E3E3Eff|*|6|*|222222ff","accordionhorizontalborderradius":"6|*|6|*|6|*|6","accordionhorizontaltabbg":"3E3E3E","accordionhorizontaltabbgactive":"87B801","accordionhorizontalthemeclass":"dark","accordionhorizontaltabfont":"{\"firsttab\":\"Text\",\"Text\":{\"color\":\"e4eaeeff\",\"size\":\"112||%\",\"tshadow\":\"0|*|1|*|0|*|000000FF\",\"afont\":\"google(@import url(http:\/\/fonts.googleapis.com\/css?family=Open Sans);),Arial\",\"lineheight\":\"normal\",\"bold\":1,\"italic\":0,\"underline\":0,\"align\":\"right\",\"paddingleft\":0},\"Active\":{\"paddingleft\":0,\"color\":\"222222ff\",\"tshadow\":\"0|*|1|*|0|*|ffffff3d\"},\"Link\":{\"paddingleft\":0},\"Hover\":{\"paddingleft\":0,\"color\":\"222222ff\",\"tshadow\":\"0|*|1|*|0|*|ffffffff\"},\"firsttab\":\"Text\"}","accordionhorizontalduration":"1000","accordionhorizontaleasing":"easeInCubic","mainafterout":"1","inaftermain":"1","controls":"0|*|0","cache":"0|*|1440","autoplay":"0|*|5000","stopautoplay":"1|*|1|*|1","resumeautoplay":"0|*|1","widgetarrow":"image","widgetarrowdisplay":"0|*|always","previousposition":"left|*|0|*|%|*|top|*|50|*|%","previous":"","nextposition":"right|*|0|*|%|*|top|*|50|*|%","next":"","widgetbullet":"text","widgetbulletdisplay":"0|*|always","bulletposition":"left|*|0|*|%|*|bottom|*|5|*|%","bulletalign":"center","bullet":"","bulletbackground":"00000060","bulletbackgroundhover":"7670C7ff","fontclassnumber":"sliderfont7","bulletbar":"none","bulletshadow":"none","bulletbarcolor":"00000060","bullethumbnail":"0|*|top","thumbnailsizebullet":"100|*|60","bulletthumbnail":"00000060","widgetautoplay":"image","widgetautoplaydisplay":"0|*|always","autoplayimageposition":"left|*|0|*|%|*|top|*|50|*|%","autoplayimage":"","widgetindicator":"pie","widgetindicatordisplay":"0|*|always","indicatorposition":"right|*|5|*|%|*|top|*|5|*|%","indicatorskin":"plugins\/nextendsliderwidgetindicator\/pie\/pie\/pie\/default.png","indicatorcolor":"ffffffff|*|00000080","indicatorsize":"25","indicatorthickness":"0.5","indicatorlinecap":"butt","widgetbar":"gradient","widgetbardisplay":"0|*|always","bargradientposition":"left|*|0|*|%|*|top|*|50|*|%","bargradient":"","bargradientheight":"35","bargradienttitlefont":"sliderfont7","bargradientdescriptionfont":"sliderfont7","widgetthumbnail":"horizontal","widgetthumbnaildisplay":"0|*|always","thumbnailposition":"left|*|0|*|%|*|bottom|*|0|*|px","thumbnailsize":"100|*|60","thumbnailperpage":"2","thumbnailanimation":"700","thumbnail":"","thumbnailactivebackground":"00000080","widgetshadow":"shadow1","widgetshadowdisplay":"0|*|always","shadowposition":"left|*|0|*|%|*|bottom|*|-5|*|%","shadowcss":""}'
        );
    }

    function getSliders() {
        $db = NextendDatabase::getInstance();
        $db->setQuery('SELECT * FROM #__nextend_smartslider_sliders');
        return $db->loadAssocList();
    }

    function renderQuickAddForm($data = array()) {
        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = dirname(__FILE__) . '/forms/quickslider.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        $form->set('class', 'nextend-smart-slider-admin');
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('slider');
        
        $group = array();
        $list = array();
        NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendSliderGeneratorList', array(&$group, &$list));
        if(isset($list['imagefromfolder']['imagefromfolder_quickimage'])){
            $path = $list['imagefromfolder']['imagefromfolder_quickimage'][1];

            $form = new NextendForm();
            $form->set('class', 'nextend-smart-slider-admin');
            $form->loadArray($data);
            
            $form->loadXMLFile($path.'config.xml');
    
            echo $form->render('generator');
        }else{
            echo 'Quick image plugin is missing!';
        }

    }

    function renderAddForm($data = array()) {
        NextendSmartSliderFontSettings::initAdminFonts();
        return $this->editForm($data);
    }

    function renderEditForm($id = 0) {
        if ($id == 0)
            $this->redirectToCreate();

        $slider = $this->getSlider($id);
        if ($slider === null)
            $this->redirectToCreate();

        $data = json_decode($slider['params'], true);
        if ($data == null)
            $data = array();
        $data['title'] = $slider['title'];
        $data['type'] = $slider['type'];
        NextendSmartSliderFontSettings::initAdminFonts($id);
        return $this->editForm($data);
    }

    function editForm($data = array()) {

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = dirname(__FILE__) . '/forms/slider.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        $form->set('class', 'nextend-smart-slider-admin');
        $form->set('manual', 'http://www.nextendweb.com/wiki/smart-slider-documentation/');
        $form->set('support', 'http://www.nextendweb.com/smart-slider#support');
        
        if(!empty($data)){
            if(!isset($data['backgroundresize'])) $data['backgroundresize'] = 0; // If old version doesn't have background resize, then leave in that way
        }
        
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('slider');
        return array($data, $configurationXmlFile, 'slider');
    }

    function create($slider) {
        if (!isset($slider['title']))
            return false;
        if ($slider['title'] == '')
            $slider['title'] = NextendText::_('New_slider');

        $db = NextendDatabase::getInstance();
        
        $title = $slider['title'];
        unset($slider['title']);
        $type = $slider['type'];
        unset($slider['type']);
        
        $db->insert('#__nextend_smartslider_sliders', array(
            'title' => $title,
            'type' => $type,
            'params' => json_encode($slider),
            'generator' => '',
            'slide' => ''
        ));

        return $db->insertid();
    }

    function import($slider) {
        if (!isset($slider['title']))
            return false;
        if ($slider['title'] == '')
            $slider['title'] = NextendText::_('New_slider');

        $db = NextendDatabase::getInstance();
        
        $db->insert('#__nextend_smartslider_sliders', array(
            'title' => $slider['title'],
            'type' => $slider['type'],
            'generator' => $slider['generator'],
            'slide' => $slider['slide'],
            'params' => $slider['params']
        ));

        return $db->insertid();
    }

    function save($id, $slider) {
        if (!isset($slider['title']) || $id <= 0)
            return false;
        if ($slider['title'] == '')
            $slider['title'] = NextendText::_('New_slider');

        $db = NextendDatabase::getInstance();
        
        $title = $slider['title'];
        unset($slider['title']);
        $type = $slider['type'];
        unset($slider['type']);
        
        $db->update('#__nextend_smartslider_sliders', array(
            'title' => $title,
            'type' => $type,
            'params' => json_encode($slider)
        ), 'id = ' . $db->quote($id));
        
        self::markChanged($id);

        return $id;
    }

    function editGeneratorForm($data = array()) {

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = dirname(__FILE__) . '/forms/generator.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        $form->set('class', 'nextend-smart-slider-admin');
        $form->set('manual', 'http://www.nextendweb.com/wiki/smart-slider-documentation/');
        $form->set('support', 'http://www.nextendweb.com/smart-slider#support');
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('generator');
        return $configurationXmlFile;
    }

    function saveGeneratorSettings($id, $generator) {

        $db = NextendDatabase::getInstance();
        
        $db->update('#__nextend_smartslider_sliders', array(
            'generator' => json_encode($generator)
        ), 'id = ' . $db->quote($id));
        
        self::markChanged($id);

        return $id;
    }

    function saveGenerator($id, $generator, $slide, $base64 = true) {

        $db = NextendDatabase::getInstance();
        
        if($base64) $slide['slide'] = base64_decode($slide['slide']);
        
        $db->update('#__nextend_smartslider_sliders', array(
            'generator' => json_encode($generator),
            'slide' => json_encode($slide)
        ), 'id = ' . $db->quote($id));

        $this->generateSlidesWithGenerator($id, $generator, $slide);
        
        self::markChanged($id);

        return $id;
    }

    function editGeneratorSlide($data = array()) {

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = dirname(__FILE__) . '/forms/generatoredit.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        $form->set('class', 'nextend-smart-slider-admin');
        $form->set('manual', 'http://www.nextendweb.com/wiki/smart-slider-documentation/');
        $form->set('support', 'http://www.nextendweb.com/smart-slider#support');
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('generator');
        return $configurationXmlFile;
    }

    function saveGeneratorSlide($id, $slide, $base64 = true) {

        $db = NextendDatabase::getInstance();
        
        if($base64) $slide['slide'] = base64_decode($slide['slide']);
        
        $db->update('#__nextend_smartslider_sliders', array(
            'slide' => json_encode($slide)
        ), 'id = ' . $db->quote($id));

        $slider = $this->getSlider($id);
        $generator = (array)json_decode($slider['generator'], true);
        
        $this->generateSlidesWithGenerator($id, $generator, $slide);
        
        self::markChanged($id);

        return $id;
    }
    
    function generateSlidesWithGenerator($id, $generator, $slide){
        $generatorParams = new NextendData();
        $generatorParams->loadArray($generator);

        $generateslides = NextendParse::parse($generatorParams->get('generateslides', '0|*|0|*|0'));
        $createslides = intval($generateslides[1]);
        if($generatorParams->get('enabled', 0) && $createslides === 1){

            nextendimportsmartslider2('nextend.smartslider.generator');

            $slidesModel = $this->getModel('slides');
            //$slidesModel->deleteBySlider($id);

            $staticslides = intval($generateslides[2]);

            $generatorSlideParams = new NextendData();
            $generatorSlideParams->loadArray($slide);


            $generator = new NextendSmartsliderGenerator($generatorParams, $generatorSlideParams, $id);

            if($staticslides){
                $slides = $generator->generateSlides($id);
                foreach($slides AS $slide){
                    unset($slide['id']);
                    $slidesModel->create($id, $slide, false);
                }
            }else{
                $slides = $generator->generateSlides($id, false);
                $slidesModel->deleteGeneratedBySlider($id);
                foreach($slides AS $k => $slide){
                    unset($slide['id']);
                    $slide['generator'] = $k+1;
                    $slidesModel->create($id, $slide, false);
                }
            }
        }
    }

    function delete($id) {
        $slidesModel = $this->getModel('slides');
        $slidesModel->deleteBySlider($id);

        $db = NextendDatabase::getInstance();
        $db->setQuery('DELETE FROM #__nextend_smartslider_sliders WHERE id=' . $db->quote($id));
        $db->query();
        
        self::markChanged($id);
    }

    function deleteslides($id) {
        $slidesModel = $this->getModel('slides');
        $slidesModel->deleteBySlider($id);
        self::markChanged($id);
    }    

    function duplicate($id) {

        $db = NextendDatabase::getInstance();

        $slider = $this->getSlider($id);
        unset($slider['id']);

        $slider['title'] .= ' - copy';
        
        $newsliderid = $this->import($slider);

        if(!$newsliderid) return false;

        $slidesModel = $this->getModel('slides');

        foreach($slidesModel->getSlides($id) AS $slide){
            unset($slide['id']);
            $slide['slider'] = $newsliderid;
            $slidesModel->create($newsliderid, $slide, false);
        }
        
        $font = NextendSmartSliderStorage::get('font'.$id);
        if($font){
            NextendSmartSliderStorage::set('font'.$newsliderid, $font);
        }

        return $newsliderid;

    }

    function redirectToCreate() {
        header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=create'));
        exit;
    }
    
    function exportSlider($id){
        nextendimport('nextend.externals.zip_lib');
        $zip = new NextendZipFile();
        $slider = $this->getSlider($id);
        $title = preg_replace('/[^a-zA-Z0-9]/', '_', $slider['title']).'.smart';
        unset($slider['id']);
        $zip->addFile(serialize($slider), 'slider.ss2');
        
        $slidesModel = $this->getModel('slides');
        $slides = $slidesModel->getSlides($id);
        for($i = 0; $i < count($slides); $i++){
            unset($slides[$i]['id']);
            unset($slides[$i]['slider']);
        }
        $zip->addFile(serialize($slides), 'slides.ss2');
        
        $fonts = NextendSmartSliderFontSettings::getAll($id);
        $zip->addFile(serialize($fonts), 'fonts.ss2');
        
        return array($title, $zip->file());
    }
    
    function exportSliderAsHTML($id){
        global $zip;
        nextendimport('nextend.externals.zip_lib');
        $zip = new NextendZipFile();
        $slider = $this->getSlider($id);
        $title = preg_replace('/[^a-zA-Z0-9]/', '_', $slider['title']).'.zip';
        
        ob_start();
        if(nextendIsJoomla()){
            nextendimportsmartslider2('nextend.smartslider.joomla.slider');
            $slider_obj = new NextendSliderJoomla(intval($id), intval($id), dirname(__FILE__));
        }else if(nextendIsWordPress()){
            global $wp_version;
            $wp_version = '1.0.0'; // Fake it to load the jQuery
            nextendimportsmartslider2('nextend.smartslider.wordpress.slider');
            $params = array();
            nextendimportsmartslider2('nextend.smartslider.wordpress.slider');
            $slider_obj = new NextendSliderWordpress(intval($id), $params, dirname(__FILE__));
        }
        $slider_obj->render();
        $body = preg_replace('/<link rel="stylesheet" href=".*?" type="text\/css" \/>/','',ob_get_clean());
        
        ob_start();
        $css = NextendCss::getInstance();
        $csslines = NextendFilesystem::readFile(NextendFilesystem::absoluteURLToPath($css->generateCSS()))."\n";
        $csslines.= NextendFilesystem::readFile(NextendFilesystem::absoluteURLToPath($css->generateCSS($slider_obj->getId())))."\n";
        
        function ss2_css_image_replace($ms){
            global $zip;
            if(substr($ms[1],0,2) == '//') $ms[1] = 'http:'.$ms[1];
            $path = NextendFilesystem::absoluteURLToPath($ms[1]);
            if(NextendFilesystem::fileexists($path)){
                if(nextendIsJoomla()){
                    $rel = str_replace('plugins/', 'images/', NextendFilesystem::pathToRelativePath($path));
                }else if(nextendIsWordpress()){
                    $rel = str_replace(NEXTEND_SMART_SLIDER2.'plugins/', 'images/', $path);
                }
                $zip->addFile(NextendFilesystem::readFile($path), $rel);
                return 'url('.$rel.')';
            }
        }
        $csslines = preg_replace_callback('/url\((.*?)\)/', 'ss2_css_image_replace', $csslines);
        
        $googlefonts = '';
        if (class_exists('NextendFontsGoogle', false)) {
            $fonts = NextendFontsGoogle::getInstance();
            $googlefonts = '<link rel="stylesheet" type="text/css" href="'.$fonts->getFontUrl().'">'."\n";
        }
        
        $zip->addFile($csslines, 'style.css');
        
        
        $js = NextendJavascript::getInstance();
        $js->generateLibraryJs();
        
        if (count($js->_jsFiles)) {
            foreach($js->_jsFiles AS $file) {
                if(substr($file, 0, 4) == 'http'){
                    $file;
                }else{
                    $js->_cache->addFile($file);
                }
            }
        }
        $js->_cache->addInline($js->_js);
        
        $jslines = NextendFilesystem::readFile(NextendFilesystem::absoluteURLToPath($js->_cache->getCache()))."\n";
        $zip->addFile($jslines, 'script.js');

        $zip->addFile('<!DOCTYPE html>
<html>
<head>
<title>'.$slider['title'].'</title>
'.$googlefonts.'<link rel="stylesheet" type="text/css" href="style.css">
<script src="script.js"></script> 
</head>
<body>
<!-- Smart Slider 2 START -->
'.$body.'
<!-- Smart Slider 2 END -->
</body>
</html>', 'index.html');
        ob_end_clean();
        
        return array($title, $zip->file());
    }
    
    function createQuick(){
        $post = NextendRequest::getVar('slider', array());
        $generatorpost = NextendRequest::getVar('generator', array());
        
        $name = $post['name'];
        
        unset($post['name']);
        
        $params = json_decode('{"size":"800|*|500|*|1","responsive":"1|*|0","globalfontsize":"12|*|16|*|20","margin":"0|*|0|*|0|*|0|*|px","simplebackgroundimage":"","simplebackgroundimagesize":"auto","simplepadding":"0|*|0|*|0|*|0","simpleborder":"0|*|3E3E3Eff","simpleborderradius":"0|*|0|*|0|*|0","simpleresponsivemaxwidth":"3000","improvedtouch":"0","simpleskins":"","simpleslidercss":"","simpleanimation":"horizontal","simpleanimationproperties":"1500|*|0|*|easeInOutQuint|*|1","simplebackgroundanimation":"0|*|bars","fadeonload":"1|*|0","playfirstlayer":"0","mainafterout":"1","inaftermain":"1","controls":"0|*|0|*|0","blockrightclick":"0","randomize":"0","autoplay":"1|*|8000","autoplayfinish":"0|*|loop|*|current","stopautoplay":"1|*|1|*|1","resumeautoplay":"0|*|1|*|0","widgetarrow":"transition","widgetarrowdisplay":"1|*|always|*|1|*|1","previousposition":"left|*|0|*|%|*|top|*|height\/2-previousheight\/2|*|%","previous":"plugins\/nextendsliderwidgetarrow\/transition\/transition\/previous\/my-test.png","nextposition":"right|*|0|*|%|*|top|*|height\/2-nextheight\/2|*|%","next":"plugins\/nextendsliderwidgetarrow\/transition\/transition\/next\/my-test.png","arrowbackground":"00000080","arrowbackgroundhover":"7670c7ff","widgetbullet":"numbers","widgetbulletdisplay":"1|*|always|*|1|*|1","bulletposition":"left|*|0|*|%|*|bottom|*|5|*|%","bulletwidth":"100%","bulletorientation":"horizontal","bulletalign":"center","bullet":"plugins\/nextendsliderwidgetbullet\/numbers\/numbers\/bullets\/square.png","bulletbackground":"00000060","bulletbackgroundhover":"7670C7ff","fontclassnumber":"sliderfont7","bulletbar":"none","bulletshadow":"none","bulletbarcolor":"00000060","bullethumbnail":"0|*|top","thumbnailsizebullet":"100|*|60","bulletthumbnail":"00000060","widgets":"arrow","backgroundresize":"cover"}', true);
        $post['autoplay'] = '1|*|8000';
        $params = $post+$params;
        
        $generator = array(
            'enabled' => 1,
            'source' => 'imagefromfolder_quickimage',
            'cachetime' => 1,
            'generateslides' => '1000|*|0|*|0',
            'generatorgroup' => 1
        );
        $generator = $generatorpost + $generator;
        
        $slide = array();
        NextendPlugin::callPlugin('nextendslidergeneratorlayouts', 'onNextendSliderGeneratorLayouts', array('image_extended', &$slide));
        $slide = $slide['default']['slide'];
        
        $slider = array(
            'title' => $name,
            'type' => 'simple',
            'params' => json_encode($params),
            'generator' => json_encode($generator),
            'slide' => json_encode($slide)
        );
        $sliderid = $this->import($slider);
        if($sliderid){
            self::markChanged($sliderid);
            return $sliderid;
        }
    }

    function editDynamicForm($data = array(), $control = 'dynamic', $onlyFilter = false) {
        
        $group = array();
        $list = array();
        NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendSliderGeneratorList', array(&$group, &$list));
        
        $cgroup = NextendRequest::getVar('group');
        $ctype = NextendRequest::getVar('type');
        if(isset($list[$cgroup]) && isset($list[$cgroup][$ctype])){

            $css = NextendCss::getInstance();
            $js = NextendJavascript::getInstance();
    
            $css->addCssLibraryFile('common.css');
            $css->addCssLibraryFile('window.css');
            $css->addCssLibraryFile('configurator.css');
    
            $configurationXmlFile = dirname(__FILE__) . '/forms/dynamic.xml';
            $js->loadLibrary('dojo');
    
            nextendimport('nextend.form.form');
            if(!$onlyFilter){
                $form = new NextendForm();
                $form->loadArray($data);
        
                $form->loadXMLFile($configurationXmlFile);
        
                echo $form->render($control);
            }
            
            $form = new NextendForm();
            $form->loadArray($data);
    
            $form->loadXMLFile($list[$cgroup][$ctype][1].'config.xml');
    
            echo $form->render($control);
            
            return $list[$cgroup][$ctype][1].'config.xml';
        }
        
        return false;
    }
    
    function createDynamic(){
        
        $group = array();
        $list = array();
        NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendSliderGeneratorList', array(&$group, &$list));
        
        $cgroup = NextendRequest::getVar('group');
        $ctype = NextendRequest::getVar('type');
        if(isset($list[$cgroup]) && isset($list[$cgroup][$ctype])){
            $slide = array();
            NextendPlugin::callPlugin('nextendslidergeneratorlayouts', 'onNextendSliderGeneratorLayouts', array($list[$cgroup][$ctype][5], &$slide));
            $slide = $slide['default']['slide'];
            
            $dynamic = NextendRequest::getVar('dynamic', array());
            
            $name = $dynamic['name'];
            $size = $dynamic['size'];
            $slides = intval($dynamic['slides']);
            $static = $dynamic['static'];
            
            unset($dynamic['name']);
            unset($dynamic['size']);
            unset($dynamic['slides']);
            unset($dynamic['static']);
            
            $generator = array(
                'enabled' => 1,
                'source' => $ctype,
                'generateslides' => $slides.($static ? '|*|1|*|1' : '|*|0|*|0'),
                'generatorgroup' => 1
            );
            $generator+=$dynamic;
            
            $slider = array(
                'title' => $name,
                'type' => 'simple',
                'params' => '{"size":"'.$size.'","responsive":"1|*|0","globalfontsize":"12|*|16|*|20","margin":"0|*|0|*|0|*|0|*|px","simplebackgroundimage":"","simplebackgroundimagesize":"auto","simplepadding":"0|*|0|*|0|*|0","simpleborder":"0|*|3E3E3Eff","simpleborderradius":"0|*|0|*|0|*|0","simpleresponsivemaxwidth":"3000","improvedtouch":"0","simpleskins":"","simpleslidercss":"","simpleanimation":"horizontal","simpleanimationproperties":"1500|*|0|*|easeInOutQuint|*|1","simplebackgroundanimation":"0|*|bars","fadeonload":"1|*|0","playfirstlayer":"0","mainafterout":"1","inaftermain":"1","controls":"0|*|0|*|0","blockrightclick":"0","randomize":"0","autoplay":"1|*|8000","autoplayfinish":"0|*|loop|*|current","stopautoplay":"1|*|1|*|1","resumeautoplay":"0|*|1|*|0","widgetarrow":"transition","widgetarrowdisplay":"1|*|always|*|1|*|1","previousposition":"left|*|0|*|%|*|top|*|height\/2-previousheight\/2|*|%","previous":"plugins\/nextendsliderwidgetarrow\/transition\/transition\/previous\/my-test.png","nextposition":"right|*|0|*|%|*|top|*|height\/2-nextheight\/2|*|%","next":"plugins\/nextendsliderwidgetarrow\/transition\/transition\/next\/my-test.png","arrowbackground":"00000080","arrowbackgroundhover":"7670c7ff","widgetbullet":"numbers","widgetbulletdisplay":"1|*|always|*|1|*|1","bulletposition":"left|*|0|*|%|*|bottom|*|5|*|%","bulletwidth":"100%","bulletorientation":"horizontal","bulletalign":"center","bullet":"plugins\/nextendsliderwidgetbullet\/numbers\/numbers\/bullets\/square.png","bulletbackground":"00000060","bulletbackgroundhover":"7670C7ff","fontclassnumber":"sliderfont7","bulletbar":"none","bulletshadow":"none","bulletbarcolor":"00000060","bullethumbnail":"0|*|top","thumbnailsizebullet":"100|*|60","bulletthumbnail":"00000060","widgets":"arrow","backgroundresize":"cover"}',
                'generator' => json_encode($generator),
                'slide' => json_encode($slide)
            );
            
            $sliderid = $this->import($slider);
            
            if($sliderid){
                if($static){

                    nextendimportsmartslider2('nextend.smartslider.generator');
        
                    $slidesModel = $this->getModel('slides');
                    //$slidesModel->deleteBySlider($id);
                    
                    $generatorParams = new NextendData();
                    $generatorParams->loadArray($generator);
        
                    $generatorSlideParams = new NextendData();
                    $generatorSlideParams->loadArray($slide);
        
        
                    $generator = new NextendSmartsliderGenerator($generatorParams, $generatorSlideParams, $sliderid);
        
                    $slides = $generator->generateSlides($sliderid);
                    foreach($slides AS $slide){
                        unset($slide['id']);
                        $slidesModel->create($sliderid, $slide, false);
                    }
                }
                self::markChanged($sliderid);
                return $sliderid;
            }
        }
    }
    
    function changeDynamicLayout($sliderid){
        $slider = $this->getSlider($sliderid);
        
        $sliderParams = new NextendData();
        $sliderParams->loadJSON($slider['params']);
        
        $sliders = array();
        NextendPlugin::callPlugin('nextendslidergeneratorlayouts', 'onNextendSliderGeneratorSlider', array(&$sliders, $sliderParams->get('size', null)));
        $sliderpreset = NextendRequest::getVar('sliderpreset', '');
        if(isset($sliders[$sliderpreset])){
            $slider = $sliders[$sliderpreset]['slider']+$slider;
            
            $db = NextendDatabase::getInstance();
            
            $db->update('#__nextend_smartslider_sliders', array(
                'type' => $slider['type'],
                'params' => $slider['params']
            ), 'id = ' . $db->quote($sliderid));
            
            self::markChanged($sliderid);            
        }
        
        
        $generatorParams = new NextendData();
        $generatorParams->loadJSON($slider['generator']);
        
        $group = array();
        $list = array();
        NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendSliderGeneratorList', array(&$group, &$list));
        
        $source = $generatorParams->get('source');
        $sourcetype = null;
        foreach($list AS $ls){
            foreach($ls AS $k => $l){
                if($k == $source){
                    $sourcetype = $l;
                    break;
                }
            }
            if($sourcetype) break;
        }
        
        $slide = array();
        NextendPlugin::callPlugin('nextendslidergeneratorlayouts', 'onNextendSliderGeneratorLayouts', array($sourcetype[5], &$slide));
        
        $layout = NextendRequest::getVar('layout', '');
        if(isset($slide[$layout])){
            if(isset($slide[$layout]['slider'])) $generatorParams->loadArray($slide[$layout]['slider']);
            
            $this->saveGenerator($sliderid, $generatorParams->toArray(), $slide[$layout]['slide'], false);
            
            
            $generateslides = NextendParse::parse($generatorParams->get('generateslides', '0|*|0|*|0'));
            $createslides = intval($generateslides[1]);
            if($generatorParams->get('enabled', 0) && $createslides === 1){
            
                nextendimportsmartslider2('nextend.smartslider.generator');
            
                $slidesModel = $this->getModel('slides');
                $slidesModel->deleteBySlider($sliderid);
            
                $staticslides = intval($generateslides[2]);
            
                $generatorSlideParams = new NextendData();
                $generatorSlideParams->loadArray($slide[$layout]['slide']);
            
                $generator = new NextendSmartsliderGenerator($generatorParams, $generatorSlideParams, $sliderid);
            
                if($staticslides){
                    $slides = $generator->generateSlides($sliderid);
                    foreach($slides AS $slide){
                        unset($slide['id']);
                        $slidesModel->create($sliderid, $slide, false);
                    }
                }else{
                    $slides = $generator->generateSlides($sliderid, false);
                    $slidesModel->deleteGeneratedBySlider($sliderid);
                    foreach($slides AS $k => $slide){
                        unset($slide['id']);
                        $slide['generator'] = $k+1;
                        $slidesModel->create($sliderid, $slide, false);
                    }
                }
            }
        }
        return $sliderid;
    }
    
    function loadGeneratorFontSet($sliderid){
        NextendSmartSliderStorage::set('font'.$sliderid, '{"sliderfont1customlabel":"Heading light","sliderfont1":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMzIwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MXwqfDAwMDAwMGM3IiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjoxLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InBhZGRpbmdsZWZ0IjowLCJzaXplIjoiMTAwfHwlIn0sIkxpbms6SG92ZXIiOnsicGFkZGluZ2xlZnQiOjAsInNpemUiOiIxMDB8fCUifX0=","sliderfont2customlabel":"Heading dark","sliderfont2":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiMDAwMDAwZGIiLCJzaXplIjoiMzIwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MHwqfGZmZmZmZjMzIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjoxLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfont3customlabel":"Subheading light","sliderfont3":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMTcwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MXwqfDAwMDAwMGM3IiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjIiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfont4customlabel":"Subheading dark","sliderfont4":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiMDAwMDAwZGIiLCJzaXplIjoiMTcwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MHwqfGZmZmZmZjMzIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjIiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfont5customlabel":"Paragraph light","sliderfont5":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMTE0fHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MXwqfDAwMDAwMGM3IiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjQiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJqdXN0aWZ5IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfont6customlabel":"Paragraph dark","sliderfont6":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiMDAwMDAwZGIiLCJzaXplIjoiMTE0fHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MHwqfGZmZmZmZjMzIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjQiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJqdXN0aWZ5IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfont7customlabel":"Small text light","sliderfont7":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiOTB8fCUiLCJ0c2hhZG93IjoiMHwqfDF8KnwxfCp8MDAwMDAwYzciLCJhZm9udCI6Imdvb2dsZShAaW1wb3J0IHVybChodHRwOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0KTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuMiIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6ImxlZnQiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfSwiTGluazpIb3ZlciI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfont8customlabel":"Small text dark","sliderfont8":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiMDAwMDAwZGIiLCJzaXplIjoiOTB8fCUiLCJ0c2hhZG93IjoiMHwqfDF8KnwwfCp8ZmZmZmZmMzMiLCJhZm9udCI6Imdvb2dsZShAaW1wb3J0IHVybChodHRwOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0KTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuMSIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6ImxlZnQiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfSwiTGluazpIb3ZlciI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfont9customlabel":"Handwritten light","sliderfont9":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMTQwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MXwqfDAwMDAwMGM3IiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9UGFjaWZpY28pOyksQXJpYWwiLCJsaW5laGVpZ2h0IjoiMS4zIiwiYm9sZCI6MCwiaXRhbGljIjowLCJ1bmRlcmxpbmUiOjAsImFsaWduIjoibGVmdCIsInBhZGRpbmdsZWZ0IjowfSwiTGluayI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rOkhvdmVyIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH19","sliderfont10customlabel":"Handwritten dark","sliderfont10":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiMDAwMDAwZGIiLCJzaXplIjoiMTQwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MHwqfGZmZmZmZjMzIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9UGFjaWZpY28pOyksQXJpYWwiLCJsaW5laGVpZ2h0IjoiMS4zIiwiYm9sZCI6MCwiaXRhbGljIjowLCJ1bmRlcmxpbmUiOjAsImFsaWduIjoibGVmdCIsInBhZGRpbmdsZWZ0IjowfSwiTGluayI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rOkhvdmVyIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH19","sliderfont11customlabel":"Button light","sliderfont11":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMTAwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MXwqfDAwMDAwMGM3IiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJjZW50ZXIiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfSwiTGluazpIb3ZlciI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfont12customlabel":"Button dark","sliderfont12":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiMDAwMDAwZGIiLCJzaXplIjoiMTAwfHwlIiwidHNoYWRvdyI6IjB8KnwxfCp8MHwqfGZmZmZmZjMzIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJjZW50ZXIiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsicGFkZGluZ2xlZnQiOjAsInNpemUiOiIxMDB8fCUifSwiTGluazpIb3ZlciI6eyJwYWRkaW5nbGVmdCI6MCwic2l6ZSI6IjEwMHx8JSJ9fQ==","sliderfontcustom5customlabel":"Post generator title v1","sliderfontcustom5":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiNmI2YjZiZmYiLCJzaXplIjoiMjIwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjoxLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJjZW50ZXIiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowLCJjb2xvciI6Ijg3ZDJjZWZmIn0sIkxpbms6SG92ZXIiOnsiY29sb3IiOiI4MmM3YzNmZiIsInNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom6customlabel":"Post generator categoryv1","sliderfontcustom6":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiODc4Nzg3ZmYiLCJzaXplIjoiMTQwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9QXZlcmFnZSk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJjZW50ZXIiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfSwiTGluazpIb3ZlciI6eyJjb2xvciI6IjY5YmRiOWZmIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom7customlabel":"Post generator paragraph v1","sliderfontcustom7":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiYTdhN2E3ZmYiLCJzaXplIjoiMTMwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9QXZlcmFnZSk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjYiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJjZW50ZXIiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowLCJjb2xvciI6IjY5YmRiOWIzIn0sIkxpbms6SG92ZXIiOnsiY29sb3IiOiI2OWJkYjlmZiIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfontcustom8customlabel":"Post generator button v1","sliderfontcustom8":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiNmNjOGMzZmYiLCJzaXplIjoiMTEwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zKTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuNSIsImJvbGQiOjEsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6ImNlbnRlciIsInBhZGRpbmdsZWZ0IjowfSwiTGluayI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rOkhvdmVyIjp7ImNvbG9yIjoiZmZmZmZmZWIiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom9customlabel":"Post generator title v2","sliderfontcustom9":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiNmI2YjZiZmYiLCJzaXplIjoiMjIwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjoxLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MCwiY29sb3IiOiI4N2QyY2VmZiJ9LCJMaW5rOkhvdmVyIjp7ImNvbG9yIjoiODJjN2MzZmYiLCJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom10customlabel":"Post generator categoryv2","sliderfontcustom10":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiYWFhYWFhZmYiLCJzaXplIjoiMTAwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zKTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuMyIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6ImxlZnQiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfSwiTGluazpIb3ZlciI6eyJjb2xvciI6IjY5YmRiOWZmIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom11customlabel":"Post generator paragraph v2","sliderfontcustom11":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiYTdhN2E3ZmYiLCJzaXplIjoiMTEwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zKTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuNiIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6Imp1c3RpZnkiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowLCJjb2xvciI6IjY5YmRiOWIzIn0sIkxpbms6SG92ZXIiOnsiY29sb3IiOiI2OWJkYjlmZiIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfontcustom12customlabel":"Post generator button v2","sliderfontcustom12":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMTAwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zKTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuNSIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6ImNlbnRlciIsInBhZGRpbmdsZWZ0IjowfSwiTGluayI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rOkhvdmVyIjp7ImNvbG9yIjoiZmZmZmZmZWIiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom13customlabel":"Post generator title v3","sliderfontcustom13":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMjIwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjoxLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MCwiY29sb3IiOiJmZmZmZmZlZCJ9LCJMaW5rOkhvdmVyIjp7ImNvbG9yIjoiODJjN2MzZmYiLCJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom14customlabel":"Post generator paragraph v3","sliderfontcustom14":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmYzQiLCJzaXplIjoiMTEwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zKTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuNiIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6Imp1c3RpZnkiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowLCJjb2xvciI6IjY5YmRiOWIzIn0sIkxpbms6SG92ZXIiOnsiY29sb3IiOiI2OWJkYjlmZiIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfontcustom15customlabel":"Post generator title v4","sliderfontcustom15":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMjcwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjoxLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJjZW50ZXIiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowLCJjb2xvciI6ImZmZmZmZmZmIn0sIkxpbms6SG92ZXIiOnsiY29sb3IiOiI4MmM3YzNmZiIsInNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom16customlabel":"Post generator paragraph v4","sliderfontcustom16":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmYzQiLCJzaXplIjoiMTIwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zKTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuNiIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6ImNlbnRlciIsInBhZGRpbmdsZWZ0IjowfSwiTGluayI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjAsImNvbG9yIjoiNjliZGI5YjMifSwiTGluazpIb3ZlciI6eyJjb2xvciI6IjY5YmRiOWZmIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom17customlabel":"Webshop generator title v1 ","sliderfontcustom17":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiNDM1NjY0ZmYiLCJzaXplIjoiMjQwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjIiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MCwiY29sb3IiOiI0MzU2NjRmZiJ9LCJMaW5rOkhvdmVyIjp7ImNvbG9yIjoiOGU0NGFkZmYiLCJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom18customlabel":"Webshop generator subtitle v1 ","sliderfontcustom18":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiNjA3MjgwZmYiLCJzaXplIjoiMTMwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjIiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MCwiY29sb3IiOiI0MzU2NjRmZiJ9LCJMaW5rOkhvdmVyIjp7ImNvbG9yIjoiOGU0NGFkZmYiLCJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom19customlabel":"Webshop generator paragraph v1","sliderfontcustom19":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiOGY5NTlkZTYiLCJzaXplIjoiMTEwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9T3BlbitTYW5zKTspLEFyaWFsIiwibGluZWhlaWdodCI6IjEuNCIsImJvbGQiOjAsIml0YWxpYyI6MCwidW5kZXJsaW5lIjowLCJhbGlnbiI6Imp1c3RpZnkiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowLCJjb2xvciI6IjQzNTY2NGZmIn0sIkxpbms6SG92ZXIiOnsiY29sb3IiOiI4ZTQ0YWRmZiIsInNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom20customlabel":"Webshop generator price v1 ","sliderfontcustom20":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiOGU0NGFkZmYiLCJzaXplIjoiMjQwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjIiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJyaWdodCIsInBhZGRpbmdsZWZ0IjowfSwiTGluayI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjAsImNvbG9yIjoiNDM1NjY0ZmYifSwiTGluazpIb3ZlciI6eyJjb2xvciI6IjdmM2M5Y2ZmIiwic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfX0=","sliderfontcustom21customlabel":"Webshop generator title v2","sliderfontcustom21":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiYjQ3MGEyZmYiLCJzaXplIjoiMTcwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9UmFsZXdheSk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsImNvbG9yIjoiZjU4NzAwZmYiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom22customlabel":"Webshop generator price v2","sliderfontcustom22":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiOGU0NGFkZmYiLCJzaXplIjoiMTYwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9TW9udHNlcnJhdCk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjIiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MCwiY29sb3IiOiI0MzU2NjRmZiJ9LCJMaW5rOkhvdmVyIjp7ImNvbG9yIjoiN2YzYzljZmYiLCJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ==","sliderfontcustom23customlabel":"Webshop generator paragraph v2","sliderfontcustom23":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiOTk5OTk5ZmYiLCJzaXplIjoiMTEwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9UmFsZXdheSk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjYiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJqdXN0aWZ5IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsImNvbG9yIjoiZjU4NzAwZmYiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom24customlabel":"Webshop generator price v3","sliderfontcustom24":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiYjQ3MGEyZmYiLCJzaXplIjoiMjYwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9QmViYXMpOyksQXJpYWwiLCJsaW5laGVpZ2h0IjoiMS41IiwiYm9sZCI6MSwiaXRhbGljIjowLCJ1bmRlcmxpbmUiOjAsImFsaWduIjoibGVmdCIsInBhZGRpbmdsZWZ0IjowfSwiTGluayI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rOkhvdmVyIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom25customlabel":"Webshop generator subtitle","sliderfontcustom25":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiOGE4YThhZmYiLCJzaXplIjoiMTEwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9UmFsZXdheSk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjIiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsImNvbG9yIjoiZjU4NzAwZmYiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom26customlabel":"Webshop generator title v3","sliderfontcustom26":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMjYwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9UmFsZXdheSk7KSxBcmlhbCIsImxpbmVoZWlnaHQiOiIxLjMiLCJib2xkIjowLCJpdGFsaWMiOjAsInVuZGVybGluZSI6MCwiYWxpZ24iOiJsZWZ0IiwicGFkZGluZ2xlZnQiOjB9LCJMaW5rIjp7InNpemUiOiIxMDB8fCUiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbms6SG92ZXIiOnsic2l6ZSI6IjEwMHx8JSIsImNvbG9yIjoiZmZmZmZmZDkiLCJwYWRkaW5nbGVmdCI6MH19","sliderfontcustom27customlabel":"Webshop generator price v4","sliderfontcustom27":"eyJmaXJzdHRhYiI6IlRleHQiLCJUZXh0Ijp7ImNvbG9yIjoiZmZmZmZmZmYiLCJzaXplIjoiMzgwfHwlIiwidHNoYWRvdyI6IjB8KnwwfCp8MHwqfGZmZmZmZjAwIiwiYWZvbnQiOiJnb29nbGUoQGltcG9ydCB1cmwoaHR0cDovL2ZvbnRzLmdvb2dsZWFwaXMuY29tL2Nzcz9mYW1pbHk9QmViYXMpOyksQXJpYWwiLCJsaW5laGVpZ2h0IjoiMS4yIiwiYm9sZCI6MCwiaXRhbGljIjowLCJ1bmRlcmxpbmUiOjAsImFsaWduIjoicmlnaHQiLCJwYWRkaW5nbGVmdCI6MH0sIkxpbmsiOnsic2l6ZSI6IjEwMHx8JSIsInBhZGRpbmdsZWZ0IjowfSwiTGluazpIb3ZlciI6eyJzaXplIjoiMTAwfHwlIiwicGFkZGluZ2xlZnQiOjB9fQ=="}');
        
        self::markChanged($sliderid);
    }
    
    function refreshCache($sliderid){
        $slider = $this->getSlider($sliderid);
        $generatorParams = new NextendData();
        $generatorParams->loadJSON($slider['generator']);
        $generatorSlideParams = new NextendData();
        $generatorSlideParams->loadJSON($slider['slide']);
        
        //refresh generator
        
        // Set the cache expired
        $cached = json_decode(NextendSmartSliderStorage::get('generator'.$sliderid), true);
        if(is_array($cached)){
            $cached['time'] = 0;
            NextendSmartSliderStorage::set('generator'.$sliderid, json_encode($cached));
        }
        
        if($generatorParams->get('enabled', 0)){
            nextendimportsmartslider2('nextend.smartslider.generator');
            $generator = new NextendSmartsliderGenerator($generatorParams, $generatorSlideParams, $sliderid);
            $source = $generatorParams->get('source', '');
            if ($source) $generator->initDatasource($source);
        }
        
        self::markChanged($sliderid);
    }

}
