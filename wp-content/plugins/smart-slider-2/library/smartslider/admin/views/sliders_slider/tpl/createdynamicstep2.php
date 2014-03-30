<?php
$this->loadFragment('headerstart');
?>
<div class="smartslider-button smartslider-save" onclick="setTimeout(function(){njQuery('#smartslider-form').submit();}, 300);"><?php echo NextendText::_('Generate'); ?></div>
<div class="smartslider-button smartslider-cancel" onclick="window.nextendsave=true;location.href='<?php echo $this->route('controller=sliders&view=sliders_slider&action=createdynamic'); ?>';"><?php echo NextendText::_('Cancel'); ?></div>
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
    <?php
    NextendForm::tokenize();
    $slidersModel = $this->getModel('sliders');
    $xml = $slidersModel->editDynamicForm(isset($_POST['dynamic']) ? $_POST['dynamic'] : array());
    ?>
    <input name="save" value="1" type="hidden"/>
</form>

<?php
$js = NextendJavascript::getInstance();

$js->addLibraryJsAssetsFile('dojo', 'form.js');
$js->addLibraryJs('dojo', '
    new NextendForm({
      container: "smartslider-form",
      data: {},
      extra: '.NextendForm::addExtra().',
      xml: "'.NextendFilesystem::toLinux(NextendFilesystem::pathToRelativePath($xml)).'",
      control_name: "dynamic",
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
