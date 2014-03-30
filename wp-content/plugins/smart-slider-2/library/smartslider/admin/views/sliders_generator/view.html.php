<?php

class NextendSmartsliderAdminViewSliders_generator extends NextendView{

    function editAction($tpl) {
        NextendSmartSliderFontSettings::initAdminFonts(NextendRequest::getInt('sliderid'));
        $this->render($tpl);
    }
}
