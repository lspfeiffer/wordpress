<?php
$this->loadFragment('headerstart');
?>
<div class="smartslider-button smartslider-save" onclick="setTimeout(function(){njQuery('#smartslider-form').submit();}, 300);"><?php echo NextendText::_('Save'); ?></div>
<?php
$this->loadFragment('headerend');
?>

<?php
$this->loadFragment('firstcolstart');
?>

<?php
$this->loadFragment('firstcol/settings');
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
    $settingsModel = $this->getModel('settings');
    $settingsModel->form($this->xml);
    ?>
    <input name="namespace" value="<?php echo $this->xml; ?>" type="hidden" />
    <input name="save" value="1" type="hidden" />
</form>

<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');
