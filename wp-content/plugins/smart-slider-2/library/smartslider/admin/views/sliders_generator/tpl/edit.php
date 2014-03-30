<?php
global $smartslidergeneratorslide, $slidegenerator, $generatorinstance;

nextendimportsmartslider2('nextend.smartslider.generator');

$slidersModel = $this->getModel('sliders');
$slider = $slidersModel->getSlider(NextendRequest::getInt('sliderid'));

$smartslidergenerator = (array)json_decode($slider['generator'], true);

$slidegenerator = null;
$generatorParams = new NextendData();
$generatorParams->loadArray($smartslidergenerator);
$source = $generatorParams->get('source', '');
if ($source) {
    $generatorSlideParams = new NextendData();
    $generatorSlideParams->loadArray(json_decode($slider['slide'], true));
    $generator = new NextendSmartsliderGenerator($generatorParams, $generatorSlideParams, NextendRequest::getInt('sliderid'));
    $generatorinstance = $generator->initDatasource($source, true);
    $slidegenerator = $generator;
}else{
    // Here should be a redirect for the generator start!
}

$this->loadFragment('headerstart');
?>
    <div class="smartslider-button smartslider-save" onclick="setTimeout(function(){njQuery('#smartslider-form').submit();}, 300);"><?php echo NextendText::_('Save'); ?></div>
    <div class="smartslider-button smartslider-cancel" onclick="window.nextendsave=true;location.href='<?php echo $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid=' . NextendRequest::getInt('sliderid')); ?>';"><?php echo NextendText::_('Cancel'); ?></div>
<?php
$this->loadFragment('headerend');
?>

<?php
$this->loadFragment('firstcolstart');
?>

<?php
$this->loadFragment('firstcol/slide');
?>

<?php
$this->loadFragment('firstcolend');
?>

<?php
$this->loadFragment('secondcolstart');
?>

<form id="smartslider-form" action="" onsubmit="return (parseInt(njQuery('#generateslidesgeneratorgenerateslides_1').val()) == 1 && parseInt(njQuery('#generateslidesgeneratorgenerateslides_2').val()) == 0 ? confirm('\'Generate slides\' without static switch will delete and generate your dynamic slides. Are you sure?') : true)" method="post">
    <?php
    NextendForm::tokenize();
    
    $xml = $slidersModel->editGeneratorSlide($smartslidergenerator);
    if(intval(NextendSmartSliderSettings::get('generatordesignermode', 1))){
        if($generator->_datasource && count($generator->_datasource)){
            echo '<script type="text/javascript">window.samplegeneratordata = '.json_encode($generator->_datasource).';</script>';
        }
    }

    $smartslidergeneratorslide = (array)json_decode($slider['slide'], true);


    $slidesModel = $this->getModel('slides');
    $slidesModel->editForm($smartslidergeneratorslide);
    ?>
    <input name="sliderid" value="<?php echo NextendRequest::getInt('sliderid'); ?>" type="hidden"/>
    <input name="save" value="1" type="hidden"/>
</form>

<?php
global $ss2sliderafterform;
echo $ss2sliderafterform;
?>

<?php
$css = NextendCss::getInstance();
$js = NextendJavascript::getInstance();

$js->addLibraryJsAssetsFile('dojo', 'form.js');
$js->addLibraryJs('dojo', '
    new NextendForm({
      container: "smartslider-form",
      data: '.($slider['generator'] ? $slider['generator'] : '{}').',
      extra: '.NextendForm::addExtra().',
      xml: "'.NextendFilesystem::toLinux(NextendFilesystem::pathToRelativePath($xml)).'",
      control_name: "generator",
      url: "'.NextendUri::ajaxUri('nextend', 'smartslider').'",
      loadedJSS: '.json_encode($js->generateArrayJs()).',
      loadedCSS: '.json_encode($css->generateArrayCSS()).'
    });
', true);
?>

<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');
