<?php
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
$this->loadFragment('firstcolend');
?>

<?php
$this->loadFragment('secondcolstart');
?>

<form id="smartslider-form" action="" onsubmit="return (parseInt(njQuery('#generateslidesgeneratorgenerateslides_1').val()) == 1 && parseInt(njQuery('#generateslidesgeneratorgenerateslides_2').val()) == 0 ? confirm('\'Generate slides\' without static switch will delete and generate your dynamic slides. Are you sure?') : true)" method="post">
    <?php
    NextendForm::tokenize();
    $slidersModel = $this->getModel('sliders');
    $slider = $slidersModel->getSlider(NextendRequest::getInt('sliderid'));

    $smartslidergenerator = (array)json_decode($slider['generator'], true);
    
    $xml = $slidersModel->editGeneratorForm($smartslidergenerator);
    
    $gotopreset = 0;
    if(isset($_REQUEST['gotopreset'])){
        $gotopreset = $_REQUEST['gotopreset'];
    }
    
    if(!isset($_REQUEST['group']) || !isset($_REQUEST['type'])){
        $source = explode('_',$smartslidergenerator['source'], 2);
        $_REQUEST['group'] = $source[0];
        $_REQUEST['type'] = $smartslidergenerator['source'];
    }else{
        $smartslidergenerator['source'] = $_REQUEST['type'];
        $gotopreset = 1;
    }
    $xml = $slidersModel->editDynamicForm($smartslidergenerator, 'generator', true);
    ?>
    <input name="generator[source]" value="<?php echo $smartslidergenerator['source']; ?>" type="hidden"/>
    <input name="sliderid" value="<?php echo NextendRequest::getInt('sliderid'); ?>" type="hidden"/>
    <input name="gotopreset" value="<?php echo $gotopreset; ?>" type="hidden"/>
    <input name="save" value="1" type="hidden"/>
</form>

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
