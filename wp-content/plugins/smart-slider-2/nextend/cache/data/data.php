<?php

class NextendCacheDataAbstract {

    static function getInstance() {

        static $instance;
        if (!is_object($instance)) {
            $instance = new NextendCacheData();
        } // if

        return $instance;
    }

    function cache($group = '', $time = 1440, $callable = null, $params = null) {

    }

    function check($group = '', $callable = null, $params = null) {

    }
}

if (nextendIsJoomla()) {
    nextendimport('nextend.cache.data.joomla');
} elseif (nextendIsWordPress()) {
    nextendimport('nextend.cache.data.wordpress');
}elseif (nextendIsMagento()) {
    nextendimport('nextend.cache.data.magento');
}else{
    nextendimport('nextend.cache.data.default');
}
