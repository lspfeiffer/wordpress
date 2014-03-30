<?php

nextendimport('nextend.mvc.model');
nextendimport('nextend.database.database');

class NextendSmartsliderAdminModelBase extends NextendModel {
    
    static function markChanged($id){
        NextendSmartSliderStorage::set('sliderchanged'.$id, '1');
    }
}