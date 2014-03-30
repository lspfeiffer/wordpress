<?php

class NextendElementTrial extends NextendElement {
    
    function fetchElement() {
        
        $image = NextendXmlGetAttribute($this->_xml, 'src');
        
        if(nextendIsWordpress()){
            $imagewp = NextendXmlGetAttribute($this->_xml, 'wpsrc');
            if($imagewp) $image = $imagewp;
        }

        return "<a href='http://www.nextendweb.com/smart-slider#pricing' target='_blank'><img src='".$image."' /></a>";
    }
}
