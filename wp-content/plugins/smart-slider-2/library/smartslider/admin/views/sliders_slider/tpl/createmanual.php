<?php
$this->loadFragment('headerstart');
?>
    <div class="smartslider-button smartslider-save" onclick="setTimeout(function(){njQuery('#smartslider-form').submit();}, 300);"><?php echo NextendText::_('Save'); ?></div>
    <div class="smartslider-button smartslider-cancel" onclick="window.nextendsave=true;location.href='<?php echo $this->route('controller=sliders&view=sliders_slider'); ?>';"><?php echo NextendText::_('Cancel'); ?></div>
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

<form id="smartslider-form" action="" method="post">
    <?php
    NextendForm::tokenize();
    $slidersModel = $this->getModel('sliders');
    $form = $slidersModel->renderAddForm();
    ?>
    <input name="save" value="1" type="hidden" />
</form>

<?php
$css = NextendCss::getInstance();
$js = NextendJavascript::getInstance();

$js->addLibraryJsAssetsFile('dojo', 'form.js');
$js->addLibraryJs('dojo', '
    new NextendForm({
      container: "smartslider-form",
      data: '.json_encode($form[0]).',
      extra: '.NextendForm::addExtra().',
      xml: "'.NextendFilesystem::toLinux(NextendFilesystem::pathToRelativePath($form[1])).'",
      control_name: "'.$form[2].'",
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
