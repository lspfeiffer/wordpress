<?php

nextendimportsmartslider2('nextend.smartslider.admin.models.base');

class NextendSmartsliderAdminModelSettings extends NextendSmartsliderAdminModelBase {

    function form($xml) {
    
        $data = array();
        switch ($xml) {
            case 'layout':
                $data = NextendSmartSliderLayoutSettings::getAll();
                break;
            case 'font':
                $data = NextendSmartSliderFontSettings::getAll(NextendRequest::getInt('sliderid'));
                break;
            case 'joomla':
                $data = NextendSmartSliderJoomlaSettings::getAll();
                break;
            default:
                $data = NextendSmartSliderSettings::getAll();
                break;
        }
        
        $this->render(dirname(__FILE__) . '/forms/settings/' . $xml . '.xml', $data);
    }
    
    function render($xmlpath, $data){

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();

        $form->loadArray($data);

        $form->loadXMLFile($xmlpath);
        echo $form->render('settings');
    
        $js->addLibraryJsAssetsFile('dojo', 'form.js');
        $js->addLibraryJs('dojo', '
            new NextendForm({
              container: "smartslider-form",
              data: ' . json_encode($form->_data) . ',
              xml: "' . NextendFilesystem::toLinux(NextendFilesystem::pathToRelativePath($xmlpath)) . '",
              control_name: "settings",
              url: "'.NextendUri::ajaxUri('nextend', 'smartslider').'",
              loadedJSS: ' . json_encode($js->generateArrayJs()) . ',
              loadedCSS: ' . json_encode($css->generateArrayCSS()) . '
            });
        ', true);
    }

    function save() {
        $namespace = NextendRequest::getCmd('namespace', 'default');
        if (isset($_REQUEST['namespace']) && isset($_REQUEST['settings'])) {
            if ($namespace == 'default')
                $namespace = 'settings';
            if($namespace == 'font' && NextendRequest::getInt('sliderid')){
                $namespace.= NextendRequest::getInt('sliderid');
                self::markChanged(NextendRequest::getInt('sliderid'));
            }
            NextendSmartSliderStorage::set($namespace, json_encode($_REQUEST['settings']));
        }
    }

    function loadglobalfonts($sliderid) {
        $this->clearfonts($sliderid);
        if($sliderid) NextendSmartSliderStorage::set('font'.$sliderid, NextendSmartSliderStorage::get('font'));
        return true;
    }

    function clearfonts($sliderid) {
        if($sliderid) NextendSmartSliderStorage::delete('font'.$sliderid);
        return true;
    }

}