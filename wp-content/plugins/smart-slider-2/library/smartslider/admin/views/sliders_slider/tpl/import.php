<?php
$this->loadFragment('headerstart');
?>
    <div class="smartslider-button smartslider-save" onclick="setTimeout(function(){njQuery('#smartslider-form').submit();}, 300);"><?php echo NextendText::_('Import'); ?></div>
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
$css = NextendCss::getInstance();
$css->addCssLibraryFile('form.css');
?>
<h2><?php echo NextendText::_('Import_slider'); ?></h2>

<?php NextendMessage::show(); ?>

<form id="smartslider-form" action="" method="post" enctype="multipart/form-data" style="margin: 15px;">
    <?php NextendForm::tokenize(); ?>
    <label for="file"><h3><?php echo NextendText::_('Choose_the_file_to_import_a_slider'); ?></h3></label>
    <input type="file" name="file" id="file" />
</form>

<?php
$this->loadFragment('secondcolend');
?>
<?php
$this->loadFragment('footer');
