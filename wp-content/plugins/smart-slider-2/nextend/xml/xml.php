<?php

function NextendXmlGetAttribute(&$xml, $attribute) {

    if (isset($xml[$attribute])) return (string)$xml[$attribute];
    return '';
}
