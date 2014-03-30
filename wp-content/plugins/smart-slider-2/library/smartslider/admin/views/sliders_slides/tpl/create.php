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
$this->loadFragment('firstcol/slide');
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
    $slidesModel = $this->getModel('slides');
    $slidesModel->renderAddForm();
    ?>
    <input name="sliderid" value="<?php echo NextendRequest::getInt('sliderid'); ?>" type="hidden" />
    <input name="save" value="1" type="hidden" />
</form>

<?php
global $ss2sliderafterform;
echo $ss2sliderafterform;
?>

<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');