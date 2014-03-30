<?php

class NextendSmartsliderAdminControllerLayouts extends NextendSmartsliderAdminController {

    function defaultAction() {
        if ($this->canDo('core.layout')) {
            $this->_viewName = 'sliders_help';
            $this->display('default', 'default');
        } else {
            $this->noaccess();
        }
    }

    function createAction() {
        if ($this->canDo('layout.create')) {
            if (NextendRequest::getInt('save')) {
                $layoutsModel = $this->getModel('layouts');
                if ($layoutid = $layoutsModel->create(NextendRequest::getVar('layout'))) {
                    if (NextendRequest::getVar('ajax')) {
                        nextendimport('nextend.parse.parse');
                        nextendimportsmartslider2('nextend.smartslider.items');
                        $items = new NextendSliderItems('nextend-smart-slider-0', true);
                        NextendSliderItems::$i['nextend-smart-slider-0'] = time();
                        $layout = $layoutsModel->getLayout($layoutid);
                        echo $items->render($layout['slide']);
                        exit;
                    }
                    header('LOCATION: ' . $this->route('controller=layouts&view=sliders_layouts&action=edit&layoutid=' . $layoutid));
                    exit;
                }
            }
            $this->display('edit', 'create');
        } else {
            $this->noaccess();
        }
    }

    function editAction() {
        if ($this->canDo('layout.edit')) {
            $layoutsModel = $this->getModel('layouts');
            if (!$layoutsModel->getLayout(NextendRequest::getInt('layoutid'))) {
                header('LOCATION: ' . $this->route('controller=layouts'));
                exit;
            }

            if (NextendRequest::getInt('save')) {
                if ($layoutid = $layoutsModel->save(NextendRequest::getInt('layoutid'), NextendRequest::getVar('layout'))) {
                    header('LOCATION: ' . $this->route('controller=layouts&view=sliders_layouts&action=edit&layoutid=' . $layoutid));
                    exit;
                }
            }
            $this->display('edit', 'edit');
        } else {
            $this->noaccess();
        }
    }

    function deleteAction() {
        if ($this->canDo('layout.delete')) {
            if ($layoutid = NextendRequest::getInt('layoutid')) {
                $layoutsModel = $this->getModel('layouts');
                $layoutsModel->delete($layoutid);
                header('LOCATION: ' . $_SERVER["HTTP_REFERER"]);
                exit;
            }
            header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider'));
            exit;
        } else {
            $this->noaccess();
        }
    }
}