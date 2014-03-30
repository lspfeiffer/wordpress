<?php

nextendimportsmartslider2('nextend.smartslider.admin.models.base');

class NextendSmartsliderAdminModelItem extends NextendSmartsliderAdminModelBase {

    function renderHelperForm($data = array()) {

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = dirname(__FILE__) . '/forms/item.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('item');
    }

    function renderForm($type, $item, $data = array()) {

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();
        
        $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin/js/itemparser.js');
        $js->addLibraryJsFile('jquery', $item[4] . 'parser.js');

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = $item[4] . 'configuration.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('item_'.$type);
    }
}

