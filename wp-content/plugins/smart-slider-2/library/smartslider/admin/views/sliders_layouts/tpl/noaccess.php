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
$this->loadFragment('firstcol/layouts');
?>

<?php
$this->loadFragment('firstcolend');
?>

<?php
$this->loadFragment('secondcolstart');
?>

<div style="width: 50%" class="box y"><h3>Limited access</h3><p><?php echo NextendText::_('Access_to_this_resource_not_allowed'); ?></p></div>

<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');
