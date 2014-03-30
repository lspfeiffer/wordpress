<?php

nextendimport('nextend.form.element.list');

class NextendElementFolders extends NextendElementList {

    function fetchElement() {

        $folder = NextendXmlGetAttribute($this->_xml, 'folder');

        if($folder === 'systemimages'){
            if(nextendIsJoomla()){
                $folder = JPATH_SITE.'/images/';
            }else if(nextendIsWordpress()){
                $folder = wp_upload_dir();
                $folder = $folder['basedir'].'/';
            }else if(nextendIsMagento()){
                $folder = Mage::getBaseDir('media').'/';
            }
        }
        $this->addFolder($folder);

        return parent::fetchElement();
    }

    function addFolder($folder){
        $this->_xml->addChild('option', $folder)->addAttribute('value', $folder);
        foreach(NextendFilesystem::folders($folder) AS $f){
            $this->addFolder($folder.$f.'/');
        }
    }
}
