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
$this->loadFragment('firstcolend');
?>

<?php
$this->loadFragment('secondcolstart');
?>

<?php
$accessModuleCreate = $this->canDo('core.create', 'com_modules');
$accessSliderCreate = $this->canDo('slider.create');
$accessSliderEdit = $this->canDo('slider.edit');
$accessSliderDelete = $this->canDo('slider.delete');
$accessSlideCreate = $this->canDo('slide.create');
$accessSlideEdit = $this->canDo('slide.edit');
$accessSlideDelete = $this->canDo('slide.delete');
$accessCore = $this->canDo('core.admin');
$j15 = !nextendIsJoomla() || version_compare(JVERSION, '1.6.0', 'ge') ? false : true;

$sliderid = NextendRequest::getInt('sliderid', 0);
$slidersModel = $this->getModel('sliders');
$slider = $slidersModel->getSlider($sliderid);
$generatorParams = new NextendData();
$generatorParams->loadJSON($slider['generator']);
?>

<div class="smartslider-button-wrap">
    <?php if ($accessSliderEdit): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=edit&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-edit"></span><?php echo NextendText::_('Edit_slider'); ?></a>
    </div>
    <?php endif; ?>
    
    <?php if ($accessSlideCreate) : ?>                
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a href="<?php echo $this->route('controller=slides&view=sliders_slides&action=create&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-create"></span><?php echo NextendText::_('Create_slide'); ?></a>
    </div>
    <?php endif; ?>
    
    <?php if ($accessSliderEdit): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a href="<?php echo $this->route('controller=slider&view=sliders_slider&action=font&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-fonts"></span><?php echo NextendText::_('Slider_fonts'); ?></a>
    </div>
    <?php endif; ?>
    
    <?php if (!$j15 && $accessSliderEdit): 
          if($generatorParams->get('enabled', null) === null):
    ?>
        <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container">
            <a href="<?php echo $this->route('controller=sliders&view=sliders_generator&action=generatorstart&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-generator"></span><?php echo NextendText::_('Generator'); ?></a>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if ($accessSliderEdit): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a href="<?php echo $this->route('controller=slider&view=sliders_slider&action=dashboard&refreshcache=1&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-refresh"></span><?php echo NextendText::_('Refresh cache'); ?></a>
    </div>
    <?php endif; ?>
    
    <?php if ($accessSliderCreate): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=duplicate&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-duplicate"></span><?php echo NextendText::_('Duplicate'); ?></a>
    </div>
    <?php endif; ?>
    
    <?php if ($accessSliderEdit): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=export&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-export"></span><?php echo NextendText::_('Export'); ?></a>
    </div>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=exporthtml&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-export"></span><?php echo NextendText::_('Export as HTML'); ?></a>
    </div>
    <?php endif; ?>
    
    <?php if ($accessSlideDelete): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a onclick="return confirm(window.ss2lang.Are_you_sure_that_you_want_to_delete_all_the_slides_of_this_slider)" href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=deleteslides&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-delete"></span><?php echo NextendText::_('Delete_slides'); ?></a>
    </div>
    <?php endif; ?>
    
    <?php if ($accessSliderEdit): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
        <a onclick="return confirm(window.ss2lang.Are_you_sure_that_you_want_to_delete_the_this_slider)" href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=delete&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-delete-slider"></span><?php echo NextendText::_('Delete'); ?></a>
    </div>
    <?php endif; ?>
    
    
</div>
<div style="clear: both;"></div>
    
<?php 
if(!$j15 && $accessSliderEdit && $generatorParams->get('enabled', null) !== null):
?>
<h2>Generator</h2>
<div class="smartslider-button-wrap">
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_generator&action=generatorstart&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-generator"></span><?php echo NextendText::_('Change source'); ?></a>
    </div>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_generator&action=generatorsettings&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-generator-settings"></span><?php echo NextendText::_('Settings'); ?></a>
    </div>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_generator&action=generatoredit&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-generator-edit-layout"></span><?php echo NextendText::_('Edit layout'); ?></a>
    </div>
    
    <?php if ($generatorParams->get('enabled', 0)): ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container">
        <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=changedynamiclayout&sliderid=' . $sliderid); ?>" class="smartslider-button-link"><span class="smartslider-icon smartslider-action-icon smartslider-action-icon-generator-layout"></span><?php echo NextendText::_('Change layout'); ?></a>
    </div>
    <?php endif; ?>
</div>
<div style="clear: both;"></div>
<?php endif; ?>

<h2>Publish slider</h2>

<?php
    $css = NextendCss::getInstance();
    $css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/publish.css');
    
    $this->sliderid = $sliderid;
    
    if(nextendIsJoomla()){
        $this->loadFragment('publish/joomla');
    }elseif(nextendIsWordPress()){
        $this->loadFragment('publish/wordpress');
    }elseif(nextendIsMagento()){
        $this->loadFragment('publish/magento');
    }
?>

<h2>Live Preview</h2>
<div style="overflow: auto; padding: 10px;">
    <?php 
    $sliderid = NextendRequest::getInt('sliderid', 0);
    
    if($sliderid){
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
        $slider->_responsive = false;
        $slider->render();
    }else{
    ?>
    <h4><?php echo NextendText::_('Access_to_this_resource_not_allowed'); ?></h4>
    <?php } ?>
</div>
<?php
$this->loadFragment('secondcolend');
?>

<?php
$this->loadFragment('footer');
?>