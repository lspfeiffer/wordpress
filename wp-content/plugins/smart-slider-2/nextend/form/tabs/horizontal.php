<?php
nextendimport('nextend.form.tab');

class NextendTabHorizontal extends NextendTab {
    
    function NextendTabHorizontal(&$form, &$xml) {
        $css = NextendCss::getInstance();
        $css->addCssLibraryFile('tabs/horizontal.css');
        parent::NextendTab($form, $xml);
    }
   
    function decorateTitle() {
        echo "<div class='nextend-horizontal-tab'>";
    }
    
    function decorateGroupStart() {

        echo "<table><tr>";
    }
    
    function decorateGroupEnd() {

        echo "</tr></table>";
        echo "</div>";
    }
    
    function decorateElement(&$el, $out, $i) {
        echo "<td>";
        echo '<div class="nextend-horizontal-label">';
        echo $out[0];
        echo '</div>';
        echo '<div class="nextend-horizontal-element">';
        echo $out[1];
        echo '</div>';
        echo "</td>";
    } 
}