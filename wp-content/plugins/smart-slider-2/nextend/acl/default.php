<?php

class NextendAcl extends NextendAclAbstract {


    function __construct() {
    }

    function authorise($array) {
        return true;
    }
}