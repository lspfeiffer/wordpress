<?php
global $smartsliderfullwidth;
$css = NextendCss::getInstance();
$css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/secondcol.css');
?>
<div class="smartslider-secondcol" style="<?php if($smartsliderfullwidth === true){?>width: 100%;<?php }?>">