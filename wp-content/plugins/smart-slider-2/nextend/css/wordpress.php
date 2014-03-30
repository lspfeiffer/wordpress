<?php

class NextendCssWordPress extends NextendCss {

    function serveCSSFile($url) {
        if ($this->_echo) {
            parent::serveCSSFile($url);
        } else {
            parent::serveCSSFile($url);
        }
    }

}