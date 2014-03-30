<?php
nextendimport('nextend.image.color');

$params = $this->_sliderParams;

$width = intval($context['width']);
$height = intval($context['height']);

$context['bgsize'] = NextendParse::parse($params->get('simplebackgroundimagesize', 'auto'));

$border = NextendParse::parse($params->get('simpleborder', '0|*|3E3E3Eff'));
$border1 = intval($border[0]);

$padding = NextendParse::parse($params->get('simplepadding', '0|*|0|*|0|*|0'));
$context['paddingt'] = $padding[0] . 'px';
$context['paddingr'] = $padding[1] . 'px';
$context['paddingb'] = $padding[2] . 'px';
$context['paddingl'] = $padding[3] . 'px';

if ($context['canvas']) {
    $width +=  2 * $border1 + $padding[1] + $padding[3];
    $height +=  2 * $border1 + $padding[0] + $padding[2];

    $context['width'] = $width . "px";
    $context['height'] = $height . "px";
}


$context['border'] = $border1 . 'px';

$rgba = NextendColor::hex2rgba($border[1]);
$context['borderrgba'] = 'RGBA(' . $rgba[0] . ',' . $rgba[1] . ',' . $rgba[2] . ',' . round($rgba[3] / 127, 2) . ')';
$context['borderhex'] = '#' . substr($border[1], 0, 6);

$borderradius = NextendParse::parse($params->get('simpleborderradius', '0|*|0|*|0|*|0'));

$context['tl'] = $borderradius[0] . 'px';
$context['tr'] = $borderradius[1] . 'px';
$context['br'] = $borderradius[2] . 'px';
$context['bl'] = $borderradius[3] . 'px';

$width = $width -  ($padding[1] + $padding[3]) - $border1 * 2;
$height = $height -  ($padding[0] + $padding[2]) - $border1 * 2;
$context['inner1height'] = $height . 'px';

$context['canvaswidth'] = $width . "px";
$context['canvasheight'] = $height . "px";
