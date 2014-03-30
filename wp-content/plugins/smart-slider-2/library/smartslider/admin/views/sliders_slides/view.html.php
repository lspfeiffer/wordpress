<?php

class NextendSmartsliderAdminViewSliders_slides extends NextendView {

    function editAction($tpl) {
        NextendSmartSliderFontSettings::initAdminFonts(NextendRequest::getInt('sliderid'));
        $this->render($tpl);
    }

}
