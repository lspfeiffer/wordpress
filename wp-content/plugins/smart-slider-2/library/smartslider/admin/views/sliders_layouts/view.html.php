<?php

class NextendSmartsliderAdminViewSliders_Layouts extends NextendView{

    function editAction($tpl) {
        NextendSmartSliderFontSettings::initAdminFonts();
        $this->render($tpl);
    }
}
