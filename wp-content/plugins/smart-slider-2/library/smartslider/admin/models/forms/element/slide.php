<?php

nextendimport('nextend.form.element.textarea');

class NextendElementSlide extends NextendElementTextarea {

    function fetchElement() {
        global $smartslidercontroller, $ss2sliderafterform;
        $this->_value = htmlspecialchars($this->_form->get($this->_name, $this->_default), ENT_QUOTES);
        $this->_form->set($this->_name, $this->_value);
        
        $html = '<div style="display:none;">'.parent::fetchElement().'</div>';
        
        $slider = null;
    
        $params = new NextendData();
        
        if(nextendIsJoomla()){
            nextendimportsmartslider2('nextend.smartslider.joomla.slider');
            
            $module = new stdClass();
            $module->id = 0;
            $params->loadArray(array('slider' => intval($_GET['sliderid'])));
            
            $slider = new NextendSliderJoomla($module, $params, dirname(__FILE__), true);
        }else if(nextendIsWordpress()){
            nextendimportsmartslider2('nextend.smartslider.wordpress.slider');
            $slider = new NextendSliderWordpress(intval($_GET['sliderid']), $params, dirname(__FILE__), true);
        }else if(nextendIsMagento()){
            nextendimportsmartslider2('nextend.smartslider.magento.slider');
            $slider = new NextendSliderMagento(intval($_GET['sliderid']), $params, dirname(__FILE__), true);
        }
        
        ob_start();
        echo "<div id='smartslider-adjust-height' style='overflow:auto; margin: 5px; padding: 5px;'>";
        $slider->render();
        echo "</div>";
        echo "<div id='smart-slider-layer-dummy' class='smart-slider-layer'></div>";
        echo '<div class="smartslider-slide-advanced-layers" style="min-width: 1600px;"></div>';
        $ss2sliderafterform = ob_get_clean();

        $css = NextendCss::getInstance();
        $css->addCssLibraryFile('jqueryui/jquery.ui.resizable.css');

        $css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/layer.css');

        $js = NextendJavascript::getInstance();
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.core.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.widget.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.mouse.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.resizable.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.draggable.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.sortable.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.droppable.min.js');

        $js->addLibraryJsFile('jquery', NEXTENDLIBRARYASSETS . 'js' . DIRECTORY_SEPARATOR . 'base64.js');
        $js->addLibraryJsFile('jquery', NEXTENDLIBRARYASSETS . 'js' . DIRECTORY_SEPARATOR . 'sortable.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'console.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'slide.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'slide.layer.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'slide.item.js');
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'slide.layout.js');
        $js->addLibraryJs('jquery', "
            njQuery(document).ready(function(){
                var el = $('#smartslider-adjust-height');
                el.height(el[0].scrollHeight+10);
                $(window).trigger('resize');
            });
            ndojo.addOnLoad(function(){
                SmartSliderAdminSlide('" . $slider->getId() . "','" . $slider->_activeSlide . "','" . $this->_id . "', '".$smartslidercontroller->route('controller=layouts&action=create')."');
            });
        ");
        
        if(NextendSmartSliderSettings::get('slideeditoralert',1)){
            $js->addLibraryJs('jquery', "
                njQuery(window).on('beforeunload', function() {
                    if (njQuery.now()-window.nextendtime > 60000 && !window.nextendsave) { // 1 min
                        return '".NextendText::_('Your_slide_settings_has_not_been_submitted_yet')."';
                    } else {
                        return;
                    }
                });
            ");
        }

        return $html;
    }

}