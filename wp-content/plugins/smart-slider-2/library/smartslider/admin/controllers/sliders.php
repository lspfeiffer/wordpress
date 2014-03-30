<?php

class NextendSmartsliderAdminControllerSliders extends NextendSmartsliderAdminController {

    function defaultAction() {
        $this->createAction();
    }

    function createAction() {
        if ($this->canDo('slider.create')) {
            $this->display('default', 'create');
        } else {
            header('LOCATION: ' . $this->route('controller=help'));
            exit;
        };
    }

    function createquickAction() {
        if ($this->canDo('slider.create')) {
            if (NextendRequest::getInt('save')) {
                $slidersModel = $this->getModel('sliders');
                if ($sliderid = $slidersModel->createQuick()) {
                    $slidersModel->loadGeneratorFontSet($sliderid);
                    header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=changedynamiclayout&fontset=1&sliderid=' . $sliderid));
                    exit;
                }
            }
            $this->display('default', 'createquick');
        } else {
            $this->noaccess();
        };
    }

    function createmanualAction() {
        if ($this->canDo('slider.create')) {
            if (NextendRequest::getInt('save')) {
                $slidersModel = $this->getModel('sliders');
                if ($sliderid = $slidersModel->create(NextendRequest::getVar('slider'))) {
                    header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=edit&sliderid=' . $sliderid));
                    exit;
                }
            }
            $this->display('default', 'createmanual');
        } else {
            $this->noaccess();
        };
    }

    function createdynamicAction() {
        if ($this->canDo('slider.create')) {
            if (NextendRequest::getInt('step') == 2) {
                if (NextendRequest::getInt('save')) {
                    $slidersModel = $this->getModel('sliders');
                    if ($sliderid = $slidersModel->createDynamic()) {
                        $slidersModel->loadGeneratorFontSet($sliderid);
                        header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=changedynamiclayout&fontset=1&sliderid=' . $sliderid));
                        exit;
                    }
                }
                $this->display('default', 'createdynamicstep2');
            }else{
                $this->display('default', 'createdynamic');
            }
        } else {
            $this->noaccess();
        };
    }

    function changedynamiclayoutAction() {
        if ($this->canDo('slider.edit')) {
            $slidersModel = $this->getModel('sliders');
            if (!$slidersModel->getSlider(NextendRequest::getInt('sliderid'))) {
                $this->redirectToSliders();
            }
            if(NextendRequest::getInt('loadfontset')){
                $slidersModel->loadGeneratorFontSet(NextendRequest::getInt('sliderid'));
            }
            if (NextendRequest::getInt('save')) {
                $slidersModel = $this->getModel('sliders');
                if ($sliderid = $slidersModel->changeDynamicLayout(NextendRequest::getInt('sliderid'))) {
                    header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid=' . $sliderid));
                    exit;
                }
            }
            $this->display('default', 'changedynamiclayout');
        } else {
            $this->noaccess();
        };
    }
    
    function dashboardAction(){
        $slidersModel = $this->getModel('sliders');
        if ($slidersModel->getSlider(NextendRequest::getInt('sliderid'))) {
            if(NextendRequest::getInt('refreshcache')){
                $slidersModel->refreshCache(NextendRequest::getInt('sliderid'));
            }
            $this->display('default', 'dashboard');
        }else{
            $this->noaccess();
        }
    }

    function editAction() {
        if ($this->canDo('slider.edit')) {
            $slidersModel = $this->getModel('sliders');
            if (!$slidersModel->getSlider(NextendRequest::getInt('sliderid'))) {
                $this->redirectToSliders();
            }

            if (NextendRequest::getInt('save')) {
                if ($sliderid = $slidersModel->save(NextendRequest::getInt('sliderid'), NextendRequest::getVar('slider'))) {
                    header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=edit&sliderid=' . $sliderid));
                    exit;
                }
            }
            $this->display('default', 'edit');
        } else {
            $this->noaccess();
        }
    }

    function deleteAction() {
        if ($this->canDo('slider.delete')) {
            if ($sliderid = NextendRequest::getInt('sliderid')) {
                $slidersModel = $this->getModel('sliders');
                $slidersModel->delete($sliderid);
            }
            $this->redirectToSliders();
        } else {
            $this->noaccess();
        };
    }

    function deleteslidesAction() {
        if ($this->canDo('slide.delete')) {
            if ($sliderid = NextendRequest::getInt('sliderid')) {
                $slidersModel = $this->getModel('sliders');
                $slidersModel->deleteslides($sliderid);
                header('LOCATION: ' . $_SERVER["HTTP_REFERER"]);
                exit;
            }
            $this->redirectToSliders();
        } else {
            $this->noaccess();
        };
    }

    function duplicateAction() {
        if ($this->canDo('slider.create')) {
            $slidersModel = $this->getModel('sliders');
            if ($this->canDo('slider.create') && ($sliderid = NextendRequest::getInt('sliderid')) && $slidersModel->getSlider($sliderid)) {
                $newsliderid = $slidersModel->duplicate($sliderid);
                header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid=' . $newsliderid));
                exit;
            }
            $this->redirectToSliders();
        } else {
            $this->noaccess();
        };
    }

    function generatorstartAction() {
        if ($this->canDo('slider.edit')) {
            $slidersModel = $this->getModel('sliders');
            if (!$slidersModel->getSlider(NextendRequest::getInt('sliderid'))) {
                $this->redirectToSliders();
            }

            if (NextendRequest::getInt('save')) {
                if ($sliderid = $slidersModel->saveGenerator(NextendRequest::getInt('sliderid'), NextendRequest::getVar('generator', ''), NextendRequest::getVar('slide', ''))) {
                    header('LOCATION: ' . $this->route('controller=sliders&view=sliders_generator&action=generator&sliderid=' . $sliderid));
                    exit;
                }
            }

            $this->display('default', 'source');
        } else {
            $this->noaccess();
        }
    }

    function generatorsettingsAction() {
        if ($this->canDo('slider.edit')) {
            $slidersModel = $this->getModel('sliders');
            if (!$slidersModel->getSlider(NextendRequest::getInt('sliderid'))) {
                $this->redirectToSliders();
            }

            if (NextendRequest::getInt('save')) {
                if ($sliderid = $slidersModel->saveGeneratorSettings(NextendRequest::getInt('sliderid'), NextendRequest::getVar('generator', ''))) {
                    if(isset($_POST['generator']) && isset($_POST['generator']['enabled']) && $_POST['generator']['enabled'] == 0){
                        header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid=' . $sliderid));
                    }else if(NextendRequest::getInt('gotopreset', 0)){
                        header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=changedynamiclayout&sliderid=' . $sliderid));
                    }else{
                        header('LOCATION: ' . $this->route('controller=sliders&view=sliders_generator&action=generatoredit&sliderid=' . $sliderid));
                    }
                    exit;
                }
            }
            $this->display('default', 'settings');
        } else {
            $this->noaccess();
        }
    }

    function generatoreditAction() {
        if ($this->canDo('slider.edit')) {
            $slidersModel = $this->getModel('sliders');
            if (!$slidersModel->getSlider(NextendRequest::getInt('sliderid'))) {
                $this->redirectToSliders();
            }

            if (NextendRequest::getInt('save')) {
                if ($sliderid = $slidersModel->saveGeneratorSlide(NextendRequest::getInt('sliderid'), NextendRequest::getVar('slide', ''))) {
                    header('LOCATION: ' . $this->route('controller=sliders&view=sliders_generator&action=generatoredit&sliderid=' . $sliderid));
                    exit;
                }
            }

            $this->display('edit', 'edit');
        } else {
            $this->noaccess();
        }
    }

    function createmoduleAction() {
        if ($this->canDo('core.create', 'com_modules')) {
            $id = NextendRequest::getInt('sliderid', 0);
            if ($id) {
                $slidersModel = $this->getModel('sliders');
                $slider = $slidersModel->getSlider($id);
                if ($slider) {
                    $title = $slider['title'];
                    if (version_compare(JVERSION, '1.6.0', 'ge')) {
                        $param = '{"config":{"slider":"' . $id . '","showtablet":"1","showcustomtablet":"0|*|","showmobile":"1","showcustommobile":"0|*|"},"moduleclass_sfx":"","cache":"0","cache_time":"900","cachemode":"itemid"}';
                        $db = JFactory::getDBO();
                        $date = JFactory::getDate();
                        $sql = "INSERT INTO #__modules (title, module, position, ordering, published, access, params, language) VALUES (" . $db->quote($title . " created on " . $date->format('Y-m-d H:i:s', true)) . ", 'mod_smartslider2', 'smartslider-".$id."', 0, 1, 1, '" . $param . "', '*')";
                        $db->setQuery($sql);
                        $db->query();
                        $moduleid = $db->insertid();
                        $sql = "INSERT INTO #__modules_menu (moduleid, menuid) VALUES (" . $moduleid . ",0)";
                        $db->setQuery($sql);
                        $db->query();
                        header('LOCATION: ' . JRoute::_('index.php?option=com_modules&filter_search=' . urlencode($title), false));
                    } else { // Joomla 1.5
                        $db = JFactory::getDBO();
                        $sql = "INSERT INTO `#__modules` (`title`, `content`, `ordering`, `position`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`, `control`) VALUES
                                                      ('Smart Slider 2', '', 0, 'smartslider-".$id."', 1, 'mod_smartslider2', 0, 0, 1, 'slider=" . $id . "\\nshowtablet=1\\nshowcustomtablet=0\\\\|*\\\\|0\\nshowmobile=1\\nshowcustommobile=0\\\\|*\\\\|0\\ncache=0\\nmoduleclass_sfx=\\n\\n', 0, 0, '');";
                        $db->setQuery($sql);
                        $db->query();
                        $moduleid = $db->insertid();
                        $sql = "INSERT INTO #__modules_menu (moduleid, menuid) VALUES (" . $moduleid . ",0)";
                        $db->setQuery($sql);
                        $db->query();
                        header('LOCATION: ' . JRoute::_('index.php?option=com_modules&client=0&task=edit&id=' . $moduleid, false));
                    }
                    exit;
                }
            }
            $this->redirectToSliders();
        } else {
            $this->noaccess();
        }
    }
    
    function fullAction() {
        if(nextendIsJoomla()){
            $this->display('default', 'full');
        }else if(nextendIsWordpress()){
            $this->display('default', 'fullwordpress');
        }
    }
    
    function exportAction() {
        if ($this->canDo('slider.edit')) {
            $id = NextendRequest::getInt('sliderid', 0);
            $slidersModel = $this->getModel('sliders');
            if ($id && $slidersModel->getSlider($id)) {
				        ob_clean();
                $file = $slidersModel->exportSlider($id);
                
                header('Content-Description: Smart Slider 2 Export');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file[0]).'"'); //<<< Note the " " surrounding the file name
                header('Content-Transfer-Encoding: binary');
                header('Connection: Keep-Alive');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                if (function_exists('mb_strlen')) {
                    header('Content-Length: ' . mb_strlen($file[1], '8bit'));
                } else {
                    header('Content-Length: ' . strlen($file[1]));
                }
                echo $file[1];
                exit;
            }else{
                $this->redirectToSliders();
            }
        } else {
            $this->noaccess();
        }
    }
    
    function importFile($targetzip, $showmessage = true){
        nextendimport('nextend.externals.zip_read');
        $zipfile = new NextendZipfile;
        $zipfile->read_zip($targetzip);
        
        if (count($zipfile->files)) {
            $data = array();
            foreach($zipfile->files AS $file){
                $data[$file['name']] = $file['data'];
            }
            $slider = isset($data['slider.ss2']) ? $data['slider.ss2'] : 0;
            if($slider){
                $slidersModel = $this->getModel('sliders');
                $sliderid = $slidersModel->import(unserialize($slider));
                if($showmessage) NextendMessage::success(NextendText::_('Success'), NextendText::_('1_Slider_imported'));
                if($sliderid){
                    $slides = isset($data['slides.ss2']) ? $data['slides.ss2'] : 0;
                    if($slides){
                        $slidesModel = $this->getModel('slides');
                        $slides = unserialize($slides);
                        foreach($slides AS $slide){
                            $slide['slider'] = $sliderid;
                            $slidesModel->create($sliderid, $slide, false);
                        }
                        if($showmessage) NextendMessage::success(NextendText::_('Success'), count($slides).'&nbsp;'.NextendText::_('Slides_imported'));
                    }
                    $fonts = isset($data['fonts.ss2']) ? $data['fonts.ss2'] : 0;
                    if($fonts){
                        NextendSmartSliderStorage::set('font'.$sliderid, json_encode(unserialize($fonts)));
                        if($showmessage) NextendMessage::success(NextendText::_('Success'), NextendText::_('1_font_set_imported'));
                    }
                    return $sliderid;
                }
            }
        }else{
            NextendMessage::error(NextendText::_('Error'), NextendText::_('Unable_to_unzip_the_file_Error_code'));                            
        }
    }
    
    function importAction() {
        if ($this->canDo('slider.edit')) {
            if(isset($_FILES['file'])){
                if(is_file($_FILES['file']['tmp_name'])){
                    $targetzip = dirname(__FILE__).'/import.smart';
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $targetzip)) {
                        $this->importFile($targetzip);
                    }else{                        
                        NextendMessage::error(NextendText::_('Error'), NextendText::_('Unable_to_move_the_file_to_the_temporary_location').$targetzip);                            
                    }
                    @unlink($targetzip);
                }else{
                    NextendMessage::error(NextendText::_('Error'), NextendText::_('There_was_an_error_in_the_uploaded_file'));
                }
            }
            $this->display('default','import');
        } else {
            $this->noaccess();
        }
    }
    
    function importlocalAction() {
        if ($this->canDo('slider.create')) {
            $path = NEXTEND_SMART_SLIDER2_ASSETS . 'admin/smart/';
            if(NextendRequest::getInt('full', 0)){
                $path.= 'full_smart/';
            }else{
                $path.= 'free_smart/';
            }
            $filepath = $path.basename(NextendRequest::getVar('slider', '')).'.smart';
            
            if(NextendFilesystem::fileexists($filepath)){
                $sliderid = $this->importFile($filepath);
                header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid=' . $sliderid));
                exit;
            }else{
                NextendMessage::error(NextendText::_('Error'), NextendText::_('The .smart file not found!'));
            }
            $this->display('default','create');
        
        } else {
            $this->noaccess();
        }
    }
    
    function exporthtmlAction() {
        if ($this->canDo('slider.edit')) {
            $id = NextendRequest::getInt('sliderid', 0);
            $slidersModel = $this->getModel('sliders');
            if ($id && $slidersModel->getSlider($id)) {
				        ob_clean();
                $file = $slidersModel->exportSliderAsHTML($id);
                
                header('Content-Description: Smart Slider 2 Exported HTML');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file[0]).'"'); //<<< Note the " " surrounding the file name
                header('Content-Transfer-Encoding: binary');
                header('Connection: Keep-Alive');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                if (function_exists('mb_strlen')) {
                    header('Content-Length: ' . mb_strlen($file[1], '8bit'));
                } else {
                    header('Content-Length: ' . strlen($file[1]));
                }
                echo $file[1];
                exit;
            }else{
                $this->redirectToSliders();
            }
        } else {
            $this->noaccess();
        }
    }
    
    function fontAction(){
        $slidersModel = $this->getModel('sliders');
        if ($this->canDo('slider.edit') && $slidersModel->getSlider(NextendRequest::getInt('sliderid'))) {
            $settingsModel = $this->getModel('settings');
            if (NextendRequest::getInt('save')) {
                if ($settingsModel->save()) {
                    header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider&action=font&sliderid=' . $sliderid));
                    exit;
                }
            }
            $this->display('font', 'font');
        } else {
            $this->noaccess();
        }
    }
    
    function redirectToSliders(){
        header('LOCATION: ' . $this->route('controller=sliders&view=sliders_slider'));
        exit;
    }

}
