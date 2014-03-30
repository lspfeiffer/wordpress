<?php
$this->loadFragment('headerstart');
?>

<?php
$this->loadFragment('headerend');
?>

<?php
$this->loadFragment('firstcolstart');
?>

<?php
$this->loadFragment('firstcolend');
?>

<?php
$this->loadFragment('secondcolstart');
?>

<?php
    $css = NextendCss::getInstance();
    $css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/create.css');
?>

<h2>Choose dynamic source</h2>
<div class="blue-container">
<?php
NextendMessage::show();

nextendimportsmartslider2('nextend.smartslider.check');

function generateDynamicThumbs($controller, $path, $group, $type, $title, $configured, $full, $available){
    ?>
    <div class="smartslider-dynamic-thumb">
        <div><?php echo $title; ?></div>
        <img src="<?php echo NextendUri::pathToUri($path) ?>" /><br />
        <?php if($full && !smartsliderIsFull()): ?>
            <a class="button small g" href="http://www.nextendweb.com/smart-slider/" target="_blank">Get full</a>
        <?php elseif(!$configured): ?>
            <a class="button small b" href="<?php echo $controller->route('controller=settings&view=sliders_settings&action='.$group); ?>" target="_blank">Configure</a>
        <?php elseif($available !== true): ?>
            <a class="button small" href="<?php echo $available; ?>" target="_blank">Check extension</a>
        <?php else: ?>
            <a class="button small b" href="<?php echo $controller->route('controller=sliders&view=sliders_slider&action=createdynamic&step=2&group='.$group.'&type='.$type); ?>">Start</a>
        <?php endif; ?>
    </div>
    <?php
}
$groups = array();
$list = array();
NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendSliderGeneratorList', array(&$groups, &$list, true));

$notconfigured = array();
$notavailable = array();

foreach($list AS $group => $sources){
    foreach($sources AS $type => $data){
        if($data[4] !== true || ($data[3] && !smartsliderIsFull())){
            if(!isset($notavailable[$group])) $notavailable[$group] = array();
            $notavailable[$group][$type] = $data;
        }else if(!$data[2]){
            if(!isset($notconfigured[$group])) $notconfigured[$group] = array();
            $notconfigured[$group][$type] = $data;
        }else{
            generateDynamicThumbs($this, NextendFilesystem::translateToMediaPath($data[1].'../dynamic.png'), $group, $type, NextendText::_($groups[$group]).': '.$data[0], $data[2], $data[3], $data[4]);
        }
    }
}

?>
</div>

<?php
if(count($notconfigured)):
?>
<h2>Not configured</h2>
<div class="blue-container">
<?php
foreach($notconfigured AS $group => $sources){
    foreach($sources AS $type => $data){
        generateDynamicThumbs($this, NextendFilesystem::translateToMediaPath($data[1].'../dynamic.png'), $group, $type, NextendText::_($groups[$group]).': '.$data[0], $data[2], $data[3], $data[4]);
    }
}
?>
</div>
<?php
endif;
?>

<?php
if(count($notavailable)):
?>
<h2>Not available</h2>
<div class="blue-container">
<?php
foreach($notavailable AS $group => $sources){
    foreach($sources AS $type => $data){
        generateDynamicThumbs($this, NextendFilesystem::translateToMediaPath($data[1].'../dynamic.png'), $group, $type, NextendText::_($groups[$group]).': '.$data[0], $data[2], $data[3], $data[4]);
    }
}

?>
</div>
<?php
endif;
?>

<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');
