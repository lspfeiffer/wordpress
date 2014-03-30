<?php

class NextendText extends NextendTextAbstract{

}

NextendText::$lng = str_replace('-', '_', get_bloginfo('language', 'raw'));