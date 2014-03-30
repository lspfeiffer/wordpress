<?php
global $smartsliderfullwidth;
$css = NextendCss::getInstance();
$css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/firstcol.css');

$js = NextendJavascript::getInstance();
$js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin/js/adminresizer.js');
?>
<div class="smartslider-cols nextend-clearfix">
<div class="smartslider-firstcol" style="<?php if ($smartsliderfullwidth === true) { ?>display: none;<?php } ?>">
        <div class="smartslider-border">
<?php
if ((NextendRequest::getCmd('controller') == 'sliders' && NextendRequest::getCmd('action') != 'generatoredit') || NextendRequest::getCmd('controller') == 'help'):
    $this->loadFragment('firstcol/sliders');
elseif (NextendRequest::getCmd('controller') == 'layouts' && NextendRequest::getCmd('action') != 'edit' && NextendRequest::getCmd('action') != 'create'):
    $this->loadFragment('firstcol/layouts');
endif;