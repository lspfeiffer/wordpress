<?php

nextendimportsmartslider2('nextend.smartslider.admin.models.base');

class NextendSmartsliderAdminModelSlides extends NextendSmartsliderAdminModelBase {

    function getSlide($id) {
        $db = NextendDatabase::getInstance();
        $db->setQuery('SELECT * FROM #__nextend_smartslider_slides WHERE id = '.$db->quote($id));
        return $db->loadAssoc();
    }

    function getSlides($sliderid = 0, $where = '') {
        $db = NextendDatabase::getInstance();
        $db->setQuery('SELECT * FROM #__nextend_smartslider_slides WHERE slider = '.$db->quote($sliderid).' '.$where.' ORDER BY ordering');
        return $db->loadAssocList();
    }

    function getMaxOrdering($sliderid = 0) {
        $db = NextendDatabase::getInstance();
        $db->setQuery('SELECT MAX(ordering) AS ordering FROM #__nextend_smartslider_slides WHERE slider = '.$db->quote($sliderid));
        $result = $db->loadAssoc();
        if(isset($result['ordering'])) return $result['ordering'];
        return 0;
    }
    
    function renderAddForm($data = array()) {
        $this->editForm($data);
    }

    function renderEditForm($id = 0) {
        if ($id == 0) $this->redirectToCreate();

        $slide = $this->getSlide($id);
        if($slide === null) $this->redirectToCreate();
        
        $data = json_decode($slide['params'], true);
        if($data == null) $data = array();
        $data += $slide;
        $data['sliderid'] = $slide['slider'];
        $this->editForm($data);
        echo '<input name="slide[generator]" value="'.$slide['generator'].'" type="hidden" />';
    }

    function editForm($data = array()) {
        
        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = dirname(__FILE__) . '/forms/slide.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        
        $data['publishdates'] = isset($data['publishdates']) ? $data['publishdates'] : ((isset($data['publish_up']) ? $data['publish_up'] : '').'|*|'.(isset($data['publish_down']) ? $data['publish_down'] : ''));
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('slide');
    }

    function create($sliderid, $slide, $base64 = true) {
        if (!isset($slide['title']))
            return false;
        if($slide['title'] == '') $slide['title'] = NextendText::_('New_slide');

        $db = NextendDatabase::getInstance();
        
        if(isset($slide['publishdates'])){
            $date = explode('|*|',$slide['publishdates']);
        }else{
            $date[0] = $slide['publish_up'];
            $date[1] = $slide['publish_down'];
            unset($slide['publish_up']);
            unset($slide['publish_down']);
        }
        $up = strtotime(isset($date[0]) ? $date[0] : '');
        $down = strtotime(isset($date[1]) ? $date[1] : '');
        
        $tmpslide = $slide;
        unset($tmpslide['title']);
        unset($tmpslide['slide']);
        unset($tmpslide['description']);
        unset($tmpslide['thumbnail']);
        unset($tmpslide['background']);
        unset($tmpslide['published']);
        unset($tmpslide['first']);
        unset($tmpslide['generator']);
        unset($tmpslide['publishdates']);
        
        $db->insert('#__nextend_smartslider_slides', array(
            'title' => $slide['title'],
            'slide' => ($base64 ? base64_decode($slide['slide']) : $slide['slide']),
            'description' => $slide['description'],
            'thumbnail' => $slide['thumbnail'],
            'background' => $slide['background'],
            'published' => (isset($slide['published']) ? $slide['published'] : 0),
            'publish_up' => date('Y-m-d H:i:s', ($up && $up > 0 ? $up : strtotime('-1 day'))),
            'publish_down' => date('Y-m-d H:i:s', ($down && $down > 0 ? $down : strtotime('+10 years'))),
            'first' => (isset($slide['first']) ? $slide['first'] : 0),
            'generator' => (isset($slide['generator']) ? $slide['generator'] : 0),
            'params' => json_encode($tmpslide),
            'slider' => $sliderid,
            'ordering' => $this->getMaxOrdering($sliderid)+1
        ));
        
        $slideid = $db->insertid();
        
        self::markChanged($sliderid);
        
        return $slideid;
    }
    
    function save($id, $slide, $base64 = true){
        if (!isset($slide['title']) || $id <= 0)
            return false;
        if($slide['title'] == '') $slide['title'] = NextendText::_('New_slide');
        $db = NextendDatabase::getInstance();
        
        if(isset($slide['publishdates'])){
            $date = explode('|*|',$slide['publishdates']);
        }else{
            $date[0] = $slide['publish_up'];
            $date[1] = $slide['publish_down'];
            unset($slide['publish_up']);
            unset($slide['publish_down']);
        }
        $up = strtotime(isset($date[0]) ? $date[0] : '');
        $down = strtotime(isset($date[1]) ? $date[1] : '');
        
        $tmpslide = $slide;
        unset($tmpslide['title']);
        unset($tmpslide['slide']);
        unset($tmpslide['description']);
        unset($tmpslide['thumbnail']);
        unset($tmpslide['published']);
        unset($tmpslide['background']);
        unset($tmpslide['publishdates']);
        unset($tmpslide['generator']);
        
        $db->update('#__nextend_smartslider_slides', array(
            'title' => $slide['title'],
            'slide' => ($base64 ? base64_decode($slide['slide']) : $slide['slide']),
            'description' => $slide['description'],
            'thumbnail' => $slide['thumbnail'],
            'background' => $slide['background'],
            'published' => (isset($slide['published']) ? $slide['published'] : 0),
            'publish_up' => date('Y-m-d H:i:s', ($up && $up > 0 ? $up : strtotime('-1 day'))),
            'publish_down' => date('Y-m-d H:i:s', ($down && $down > 0 ? $down : strtotime('+10 years'))),
            'params' => json_encode($tmpslide)
        ), 'id = '.$db->quote($id));

        self::markChanged(NextendRequest::getInt('sliderid'));
        
        return $id;
    }
    
    function delete($id){
        $db = NextendDatabase::getInstance();
        $db->setQuery('DELETE FROM #__nextend_smartslider_slides WHERE id='.$db->quote($id));
        $db->query();
        
        self::markChanged(NextendRequest::getInt('sliderid'));
        
    }

    function duplicate($id, $sliderid = null){
        $slide = $this->getSlide($id);
        unset($slide['id']);
        $slide['published'] = 0;
        $slide['title'].= ' - copy';
        return $this->create($slide['slider'], $slide, false);
    }
    
    function first($sliderid, $id){
        $db = NextendDatabase::getInstance();
        
        $db->update('#__nextend_smartslider_slides', array(
            'first' => 0
        ), 'slider = '.$db->quote($sliderid));
        
        $db->update('#__nextend_smartslider_slides', array(
            'first' => 1
        ), 'id = '.$db->quote($id));
        
        self::markChanged($sliderid);
        
    }
    
    function publish($id){
        $db = NextendDatabase::getInstance();
        $db->update('#__nextend_smartslider_slides', array(
            'published' => 1
        ), 'id = '.$db->quote($id));
    }
    
    function unpublish($id){
        $db = NextendDatabase::getInstance();
        $db->update('#__nextend_smartslider_slides', array(
            'published' => 0
        ), 'id = '.$db->quote($id));
        
        self::markChanged(NextendRequest::getInt('sliderid'));
        
    }
    
    function deleteBySlider($sliderid){
        $db = NextendDatabase::getInstance();
        $db->setQuery('DELETE FROM #__nextend_smartslider_slides WHERE slider = '.$db->quote($sliderid));
        $db->query();
        
        self::markChanged(NextendRequest::getInt('sliderid'));
    }

    function deleteGeneratedBySlider($sliderid){
        $db = NextendDatabase::getInstance();
        $db->setQuery('DELETE FROM #__nextend_smartslider_slides WHERE generator > 0 AND slider = '.$db->quote($sliderid));
        $db->query();
        
        self::markChanged(NextendRequest::getInt('sliderid'));
    }
    
    function order($sliderid, $ids){
        if(is_array($ids) && count($ids) > 0){
            $db = NextendDatabase::getInstance();
            $i = 0;
            foreach($ids AS $id){
                $id = intval($id);
                if($id > 0){
                    $db->update('#__nextend_smartslider_slides', array(
                        'ordering' => $i
                    ), 'id = '.$db->quote($id). ' AND slider = '.$db->quote($sliderid));

                    $i++;
                }
            }
        
            self::markChanged($sliderid);
            
            return $i;
        }
        return false;
    }
}