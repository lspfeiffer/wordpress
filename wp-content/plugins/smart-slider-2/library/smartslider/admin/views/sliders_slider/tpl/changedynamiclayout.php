<?php
$this->loadFragment('headerstart');

$sliderid = NextendRequest::getInt('sliderid');

if(nextendIsJoomla()){
    nextendimportsmartslider2('nextend.smartslider.joomla.slider');

    $slider = new NextendSliderJoomla($sliderid, $sliderid, dirname(__FILE__));
}else if(nextendIsWordpress()){
    nextendimportsmartslider2('nextend.smartslider.wordpress.slider');
    $d = array();
    $slider = new NextendSliderWordpress($sliderid, $d, dirname(__FILE__));
}else if(nextendIsMagento()){
    nextendimportsmartslider2('nextend.smartslider.magento.slider');
    $d = array();
    $slider = new NextendSliderMagento($sliderid, $d, dirname(__FILE__));
}
$slidersModel = new NextendSmartsliderAdminModelSliders(null);
$fakeslider = $slidersModel->getSlider($sliderid);

global $layout, $sliderpreset;

$sliderParams = new NextendData();
$sliderParams->loadJSON($fakeslider['params']);

$sliders = array();
NextendPlugin::callPlugin('nextendslidergeneratorlayouts', 'onNextendSliderGeneratorSlider', array(&$sliders, $sliderParams->get('size', null)));
$sliderpreset = NextendRequest::getVar('sliderpreset', '');
if(isset($sliders[$sliderpreset])){
    $fakeslider = $sliders[$sliderpreset]['slider']+$fakeslider;
}

$slider->_slider = new NextendData();
$slider->_slider->loadArray($fakeslider);

$slider->_sliderParams = new NextendData();
$slider->_sliderParams->loadJSON($fakeslider['params']);

$slider->_generatorParams = new NextendData();
$slider->_generatorParams->loadJSON($fakeslider['generator']);

if($fakeslider['slide'] == '' && !isset($_REQUEST['layout'])){
    $_REQUEST['layout'] = 'default';
}
$slider->_generatorSlideParams = new NextendData();
$slider->_generatorSlideParams->loadJSON($fakeslider['slide']);

$group = array();
$list = array();
NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendSliderGeneratorList', array(&$group, &$list));

$source = $slider->_generatorParams->get('source');
$sourcetype = null;
foreach($list AS $ls){
    foreach($ls AS $k => $l){
        if($k == $source){
            $sourcetype = $l;
            break;
        }
    }
    if($sourcetype) break;
}

$slide = array();
NextendPlugin::callPlugin('nextendslidergeneratorlayouts', 'onNextendSliderGeneratorLayouts', array($sourcetype[5], &$slide));

$layout = NextendRequest::getVar('layout', '');
if(isset($slide[$layout])){
    if(isset($slide[$layout]['slider'])) $slider->_generatorParams->loadArray($slide[$layout]['slider']);
    
    $slider->_generatorSlideParams->loadArray($slide[$layout]['slide']);
}
$slider->_generatorParams->set('generateslides', intval($slider->_generatorParams->get('generateslides')).'|*|0|*|0');

function generateDynamicThumbs($controller, $image, $sliderid, $id, $data){
    global $layout, $sliderpreset;
    ?>
    <div class="smartslider-dynamic-thumb <?php echo ($layout == $id ? 'selected' : 'notselected') ?>" onclick="location.href='<?php echo $controller->route('controller=sliders&view=sliders_slider&action=changedynamiclayout&fontset='.NextendRequest::getInt('fontset', 0).'&sliderid='.$sliderid.'&sliderpreset='.$sliderpreset.'&layout='.($layout == $id ? '' : $id)); ?>'">
        <div><?php echo $data['title']; ?></div>
        <img src="<?php echo NextendUri::pathToUri(NextendFilesystem::translateToMediaPath($image)) ?>" />
    </div>
    <?php
}

function generateDynamicSliderThumbs($controller, $image, $sliderid, $id, $data){
    global $layout, $sliderpreset;
    ?>
    <div class="smartslider-dynamic-thumb <?php echo ($sliderpreset == $id ? 'selected' : 'notselected') ?>" onclick="location.href='<?php echo $controller->route('controller=sliders&view=sliders_slider&action=changedynamiclayout&fontset='.NextendRequest::getInt('fontset', 0).'&sliderid='.$sliderid.'&sliderpreset='.($sliderpreset == $id ? '' : $id).'&layout='.$layout); ?>'">
        <div><?php echo $data['title']; ?></div>
        <img src="<?php echo NextendUri::pathToUri(NextendFilesystem::translateToMediaPath($image)) ?>" />
    </div>
    <?php
}


?>
<div class="smartslider-button smartslider-save" onclick="setTimeout(function(){njQuery('#smartslider-form').submit();}, 300);"><?php echo NextendText::_('Save'); ?></div>
<div class="smartslider-button smartslider-cancel" onclick="window.nextendsave=true;location.href='<?php echo $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid='.$sliderid); ?>';"><?php echo NextendText::_('Cancel'); ?></div>
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
<form id="smartslider-form" action="" method="post">
<?php NextendForm::tokenize(); ?>
<input type="hidden" name="layout" value="<?php echo $layout;?>" />
<input type="hidden" name="save" value="1" />
<h2>Choose slider preset - optional</h2>
<div class="blue-container">
    <?php
    foreach($sliders AS $id => $data){
        generateDynamicSliderThumbs($this, $sliders[$id]['image'], $sliderid, $id, $data);
    }
    ?>
    <div style="clear:both;height: 10px; width:100%"></div>
</div>

<h2>Choose layout - optional</h2>
<div class="blue-container">
    <?php
    foreach($slide AS $id => $data){
        generateDynamicThumbs($this, $slide[$id]['image'], $sliderid, $id, $data);
    }
    ?>
    <div style="clear:both;height: 10px; width:100%"></div>
</div>

</form>

<h2>Preview</h2>
<div style="overflow: auto; padding: 10px;">
    <?php 
    
    $slider->_replaceSlider = $fakeslider;
    $slider->render();
    ?>
</div>
<?php if(NextendRequest::getInt('fontset', 0) == 0) : ?>
<div class="box" style="width: 50%">
    <h3>Missing fonts?</h3>
    <p>These layouts need custom font set to display properly. If font set have not loaded yet, you should load them.<br />
    Note: This action will clear the current font set on this slider.</p>
    <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=changedynamiclayout&loadfontset=1&fontset=1&sliderid='.$sliderid.'&layout='.NextendRequest::getVar('layout')); ?>" style="margin-top: 10px;" class="button b">Load font set</a>
</div>
<?php endif; ?>

<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');
