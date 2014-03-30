<?php
$this->loadFragment('headerstart');
?>
    <div class="smartslider-button smartslider-save" onclick="setTimeout(function(){njQuery('#smartslider-form').submit();}, 300);"><?php echo NextendText::_('Generate'); ?></div>
    <div class="smartslider-button smartslider-cancel" onclick="window.nextendsave=true;location.href='<?php echo $this->route('controller=sliders&view=sliders_slider&action=create'); ?>';"><?php echo NextendText::_('Cancel'); ?></div>
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
    $css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/createquick.css');          
    
    $js = NextendJavascript::getInstance();
    $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.core.min.js');
    $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.widget.min.js');
    $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.mouse.min.js');
    $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.sortable.min.js');
?>

<form id="smartslider-form" action="" method="post">
    <?php
    NextendForm::tokenize();
    $slidersModel = $this->getModel('sliders');
    $form = $slidersModel->renderQuickAddForm();
    ?>
    <input name="save" value="1" type="hidden" />
</form>

<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');
