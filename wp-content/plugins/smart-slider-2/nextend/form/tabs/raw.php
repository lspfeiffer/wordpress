<?php

nextendimport('nextend.form.tab');

class NextendTabRaw extends NextendTab {

    function decorateGroupStart() {
        
    }

    function decorateGroupEnd() {

        echo "</div>";
    }

    function decorateElement(&$el, $out, $i) {

        echo $out[1];
    }

}