<?php

class NextendSmartsliderAdminControllerSettings extends NextendSmartsliderAdminController {

    function NextendSmartsliderAdminControllerSettings($key) {
        parent::NextendSmartsliderAdminController($key);

    }

    function defaultAction($form = 'default') {
        if ($this->canDo('core.admin')) {
            $settingsModel = $this->getModel('settings');
            if (NextendRequest::getInt('save')) {
                if ($settingsModel->save()) {
                    header('LOCATION: ' . $this->route('controller=settings'));
                    exit;
                }
            }
            if($form == 'default' && NextendRequest::getVar('action') != $form) $form = 'plugin';
            $this->display($form, 'default');
        }else{
            $this->noaccess();
        }
    }

    function layoutAction() {
        $this->defaultAction('layout');
    }

    function fontAction() {
        $this->defaultAction('font');
    }

    function clearfontsAction() {
        if ($this->canDo('core.admin')) {
            $sliderid = NextendRequest::getInt('sliderid');
            $settingsModel = $this->getModel('settings');
            if ($sliderid) {
                if ($settingsModel->clearfonts($sliderid)) {
                    header('LOCATION: ' . $this->route('controller=settings&view=sliders_settings&action=font&sliderid='.$sliderid));
                    exit;
                }
            }
            $this->display($form, 'default');
        }else{
            $this->noaccess();
        }
    }

    function joomlaAction() {
        if(nextendIsJoomla()) $this->defaultAction('joomla');
    }

}
