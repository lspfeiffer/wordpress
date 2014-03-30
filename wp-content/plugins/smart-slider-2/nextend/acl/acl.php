<?php

class NextendAclAbstract {

    function authorise($array) {
        return true;
    }
}
if (nextendIsJoomla()) {
    nextendimport('nextend.acl.joomla');
} else {
    nextendimport('nextend.acl.default');
}