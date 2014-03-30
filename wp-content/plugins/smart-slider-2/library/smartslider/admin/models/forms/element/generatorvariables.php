<?php

class NextendElementGeneratorvariables extends NextendElement {

    function fetchElement() {
        global $generatorinstance;
        $generatorinstance->initAdmin();
        return '<div id='.$this->_id.'>'.$generatorinstance->generateList().'</div>';
    }

}