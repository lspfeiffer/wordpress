<?php

nextendimport('nextend.database.database');

class NextendGeneratorAbstract {

    var $_data;

    var $_variables;

    var $_generatorgroup;

    function NextendGeneratorAbstract($data) {
        $this->_data = $data;
        $this->_generatorgroup = intval($this->_data->get('generatorgroup', 1));
        if($this->_generatorgroup < 1) $this->_generatorgroup = 1;
    }

    function getData($number){

    }

    function initAdmin() {

        $js = NextendJavascript::getInstance();

        $js->addLibraryJs('jquery', '
            window.selectText = function(element) {
                var doc = document;

                if (doc.body.createTextRange) { // ms
                    var range = doc.body.createTextRange();
                    range.moveToElementText(element);
                    range.select();
                } else if (window.getSelection) { // moz, opera, webkit
                    var selection = window.getSelection();
                    var range = doc.createRange();
                    range.selectNodeContents(element);
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            }
            $("#generatorvariables").html("'.str_replace('"','\"', $this->generateList()).'");
            $("#generatorvariables .nextend-variables > span").qtip({
                position: {
                    my: "bottom center",
                    at: "top center"
                }
            });
        ');
    }
    
    function generateList(){
        $html = '';

        for($i = 1; $i <= $this->_generatorgroup; $i++){
            $html.= '<p class="nextend-variables">';
            foreach($this->_variables AS $k => $v){
                $html.='<span class="nextend-variable nextend-variable-hastip" title="'.$v.' '.NextendText::_('for').' '.$i.'. '.NextendText::_('record_in_group').'" onClick="selectText(this);">{|'.$k.'-'.$i.'|}</span> ';
            }
            $html.= "</p>";
        }
        $html.='<style>.nextend-variables{line-height: 20px; font-size: 13px;}.nextend-variable{margin: 0 5px;}</style>';
        return $html;
    }
}