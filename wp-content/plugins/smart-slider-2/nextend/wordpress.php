<?php

function nextendimportsmartslider2($key) {
    $keys = explode('.', $key);
    array_shift($keys);
    require_once(NEXTEND_SMART_SLIDER2. 'library/' . implode(DIRECTORY_SEPARATOR, $keys) . '.php');
}

function nextendimportaccordionmenu($key) {
    $keys = explode('.', $key);
    array_shift($keys);
    require_once(NEXTEND_ACCORDION_MENU. 'library/' . implode(DIRECTORY_SEPARATOR, $keys) . '.php');
}

function nextendSubLibraryPath($subLibrary) {
    switch($subLibrary){
        case 'accordionmenu':
            return NEXTEND_ACCORDION_MENU . 'library/' . $subLibrary . '/';
        case 'smartslider':
            return NEXTEND_SMART_SLIDER2 . 'library/' . $subLibrary . '/';
            
    }
    return NEXTENDLIBRARY . $subLibrary . '/';
}