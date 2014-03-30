<?php

nextendimportsmartslider2('nextend.smartslider.settings');
nextendimportsmartslider2('nextend.smartslider.check');
nextendimport('nextend.mvc.controller');
nextendimport('nextend.environment.request');
nextendimport('nextend.message.message');
nextendimport('nextend.language.language');

NextendText::l('common');
NextendText::l('common', nextendSubLibraryPath('smartslider').'languages/');

require_once(dirname(__FILE__).'/jslanguage.php');

class NextendSmartsliderAdminController extends NextendController {

    var $_name = 'smartslideradmin';

    function NextendSmartsliderAdminController($key) {
        global $smartslidercontroller;
        $smartslidercontroller = $this;
        
        parent::NextendController($key);
        if(!$this->canDo('core.manage')){
            $this->access = 0;
            $this->message(NextendText::_('Access not allowed to this resource.'));
            return;
        };

        nextendimport('nextend.css.css');
        $css = NextendCss::getInstance();

        $css->addCssLibraryFile('ni.css');

        $this->_baseControllerPath = dirname(__FILE__);

        $this->_routeMap = array(
            'sliders' => array(
                'sliders_slider' => array(
                    'default',
                    'create',
                    'edit',
                    'full'
                ),
                'sliders_generator' => array(
                    'default',
                    'edit'
                )
            ),
            'slides' => array(
                'sliders_slides' => array(
                    'default',
                    'create',
                    'edit',
                    'order'
                )
            ),
            'layouts' => array(
                'sliders_layouts' => array(
                    'default',
                    'create',
                    'edit',
                )
            ),
            'settings' => array(
                'sliders_settings' => array(
                    'default',
                    'layout',
                    'font'
                )
            ),
            'help' => array(
                'sliders_help' => array(
                    'default'
                )
            )
        );

        $this->init();

        if (nextendIsJoomla()) {
            $this->_router->_baseurl = 'index.php?option=com_smartslider2';
        }else if (nextendIsWordpress()) {
            $this->_router->_baseurl = 'admin.php?page=nextend-smart-slider2';
        }
    }

    function defaultAction() {

        $this->display('default');
    }
    
    function problems(){
        $count = 0;
        if(nextendIsWordPress()){
            $nextenderror = get_option( 'nextend_error' );
            if ( $nextenderror !== false && is_array($nextenderror)){
                if(isset($nextenderror['missinghead'])){
                    foreach($nextenderror['missinghead'] AS $url){
                        $count++;
                    }
                }
                if(isset($nextenderror['missingfooter'])){
                    foreach($nextenderror['missingfooter'] AS $url){
                        $count++;
                    }
                }
            }
        }
        return $count;
    }

}
