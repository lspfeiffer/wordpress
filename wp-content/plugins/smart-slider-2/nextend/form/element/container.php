<?php

class NextendElementContainer extends NextendElement {

    function fetchElement() {

        return "<div id='".$this->_id."'></div>";
    }
}
