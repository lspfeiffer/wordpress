<?php
$js = NextendJavascript::getInstance();

$accessSliderCreate = $this->canDo('slider.create');
$accessSliderEdit = $this->canDo('slider.edit');
$accessSliderDelete = $this->canDo('slider.delete');
$accessSlideCreate = $this->canDo('slide.create');
$accessSlideEdit = $this->canDo('slide.edit');
$accessSlideDelete = $this->canDo('slide.delete');
$accessCore = $this->canDo('core.admin');

$isJ = nextendIsJoomla();
$isWP = nextendIsWordPress();
$isMage = nextendIsMagento();

$j15 = !$isJ || version_compare(JVERSION, '1.6.0', 'ge') ? false : true;
?>

<div class="smartslider-button-wrap">
<?php
$slidersModel = $this->getModel('sliders');
$sliderid = NextendRequest::getInt('sliderid');
if(NextendRequest::getCmd('action', '') == '' || (NextendRequest::getCmd('view', 'sliders_slider') == 'sliders_slider' && NextendRequest::getCmd('action', 'create') == 'create')){
    $sliders = $slidersModel->getSliders();
    if ($accessSliderCreate) :
        ?>
            <div class="smartslider-button smartslider-import smartslider-button-grey smartslider-button-blue-active smartslider-icon-container <?php echo NextendRequest::getCmd('view') == 'sliders_slider' && NextendRequest::getCmd('action') == 'import' ? 'active' : ''; ?>">
                <a class="smartslider-button-link"
                   href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=import'); ?>"><span
                        class="smartslider-icon smartslider-icon-import"></span><?php echo NextendText::_('Import slider'); ?></a>
            </div>
    <?php endif; ?>
    <?php
}else{
    $slider = $slidersModel->getSlider($sliderid);
    $sliders = $slider ? array($slider) : array();
    $action = NextendRequest::getCmd('action', '');
    $url = $this->route('controller=sliders&view=sliders_slider');
    $label = 'Back to sliders';
    if($action == 'createdynamic' && NextendRequest::getCmd('step') == 2){
        $url = $this->route('controller=sliders&view=sliders_slider&action=createdynamic');
        $label = 'Back to create  dynamic slider';
    }elseif($action != 'dashboard' && $sliderid){
        $url = $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid='.$sliderid);
        $label = 'Back to dashboard';
    }
    ?>
    <div class="smartslider-button smartslider-back smartslider-button-grey smartslider-button-blue-active smartslider-icon-container">
        <a class="smartslider-button-link"
           href="<?php echo $url; ?>"><span
                class="smartslider-icon smartslider-icon-back"></span><?php echo NextendText::_($label); ?></a>
    </div>
    <?php
}
?>
</div>
<div style="clear: both;"></div>

<dl class="smartslider-list smartslider-sliders-list">
    <?php
    $i = 0;
    foreach ($sliders AS $slider):
        $c = $i % 2 ? 'even' : 'odd';
        $i++;
        $active = $sliderid == $slider['id'];

        ?>
        <dt class="<?php echo $c; ?> smartslider-button-blue-active smartslider-icon-container <?php echo $active ? 'subactive' : ''; ?> <?php echo $active && NextendRequest::getCmd('controller') == 'sliders' ? 'active' : ''; ?>">
            <a class="smartslider-button-link"
               href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=dashboard&sliderid=' . $slider['id']); ?>"><?php echo $slider['title']; ?></a>
               
            <div class="tooltip-actions" style="display: none;">
                <ul class="sidebar-tooltip-menu">

                    <?php if ($accessSliderEdit): ?>
                        <li class="smartslider-icon-container">
                            <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=edit&sliderid=' . $slider['id']); ?>">
                                <span class="smartslider-qtip-icon create_slider"></span>
                                <?php echo NextendText::_('Edit_slider'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($accessCore): ?>
                        <li class="smartslider-icon-container">
                            <a href="<?php echo $this->route('controller=slider&view=sliders_slider&action=font&sliderid=' . $slider['id']); ?>">
                                <span class="smartslider-qtip-icon fontsettings"></span>
                                <?php echo NextendText::_('Slider_fonts'); ?>
                                <?php if (NextendSmartSliderStorage::get('font'.$slider['id'])): ?>
                                    <span class="smartslider-qtip-icon activegreen"></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($accessSliderCreate): ?>
                        <li class="smartslider-icon-container">
                            <a href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=duplicate&sliderid=' . $slider['id']); ?>">
                                <span class="smartslider-qtip-icon duplicate"></span>
                                <?php echo NextendText::_('Duplicate'); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($accessSliderEdit): ?>
                        <li class="smartslider-icon-container">
                            <a onclick="return confirm(window.ss2lang.Are_you_sure_that_you_want_to_delete_the_this_slider)" href="<?php echo $this->route('controller=sliders&view=sliders_slider&action=delete&sliderid=' . $slider['id']); ?>">
                                <span class="smartslider-qtip-icon delete"></span>
                                <?php echo NextendText::_('Delete'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if ($active && NextendRequest::getCmd('controller') != 'sliders'): ?>
                <span class="smartslider-arrowdown-border"></span>
                <span class="smartslider-arrowdown"></span>
            <?php endif; ?>
        </dt>
        <dd class="<?php echo $active ? 'active' : ''; ?>">
            <?php if ($sliderid == $slider['id']): ?>
                <?php
                $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.core.min.js');
                $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.widget.min.js');
                $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.mouse.min.js');
                $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.sortable.min.js');
                $js->addLibraryJsFile('jquery', NEXTEND_SMART_SLIDER2_ASSETS . 'admin/js/slideordering.js');

                $js->addInlineJs('njQuery(document).ready(function() { '
                . 'window.smartSliderSlideOrdering.init("' . $this->route('controller=slides&view=sliders_slides&action=order&sliderid=' . $slider['id']) . '"); '
                . '});');

                $js->addInlineJs('njQuery(document).ready(function() { '
                . ' '
                . '});');
                ?>
                <ul class="smartslider-slides-list">
                    <?php if ($accessSlideCreate) : ?>
                        <li class="smartslider-button-grey smartslider-button-blue-active smartslider-icon-container <?php echo NextendRequest::getCmd('view') == 'sliders_slides' && NextendRequest::getCmd('action') == 'create' ? 'active' : ''; ?>">
                            <a class="smartslider-button-link"
                               href="<?php echo $this->route('controller=slides&view=sliders_slides&action=create&sliderid=' . $sliderid); ?>">
                                <span class="smartslider-icon smartslider-icon-smalladd"></span>
                                <?php echo NextendText::_('Create_slide'); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php
                    $slidesModel = $this->getModel('slides');
                    $slides = $slidesModel->getSlides($sliderid);
                    $slideid = NextendRequest::getInt('slideid');
                    foreach ($slides AS $slide):
                        $activeslide = $slideid == $slide['id'];
                        ?>
                        <li id="slideorder_<?php echo $slide['id']; ?>"
                            class="smartslider-slide smartslider-icon-container smartslider-button-blue-active <?php echo $activeslide ? 'active' : ''; ?>">
                            <a class="smartslider-button-link"
                               href="<?php echo $this->route('controller=slides&view=sliders_slides&action=edit&sliderid=' . $slider['id'] . '&slideid=' . $slide['id']); ?>">
                                <span class="smartslider-icon smartslider-icon-ordering"></span>
                                <?php echo $slide['title']; ?>
                            </a>
                            <div class="tooltip-actions" style="display: none;">
                                <ul class="sidebar-tooltip-menu">
                                    <?php if ($accessSlideEdit): ?>
                                        <li class="smartslider-icon-container">
                                            <a href="<?php echo $this->route('controller=slides&view=sliders_slides&action=duplicate&sliderid=' . $slider['id'] . '&slideid=' . $slide['id']); ?>">
                                                <span class="smartslider-qtip-icon duplicate"></span>
                                                <?php echo NextendText::_('Duplicate'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($accessSlideDelete): ?>
                                        <li class="smartslider-icon-container">
                                            <a onclick="return confirm(window.ss2lang.Are_you_sure_that_you_want_to_delete_the_slide)" href="<?php echo $this->route('controller=slides&view=sliders_slides&action=delete&sliderid=' . $slider['id'] . '&slideid=' . $slide['id']); ?>">
                                                <span class="smartslider-qtip-icon subdelete"></span>
                                                <?php echo NextendText::_('Delete'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php if ($accessSlideEdit) : ?>
                                <?php if ($slide['first']): ?>
                                    <a class="smartslider-icon smartslider-icon-starred"
                                       href="<?php echo $this->route('controller=slides&view=sliders_slides&action=first&sliderid=' . $slider['id'] . '&slideid=' . $slide['id']); ?>">First
                                        slide</a>
                                <?php else: ?>
                                    <a class="smartslider-icon smartslider-icon-star"
                                       href="<?php echo $this->route('controller=slides&view=sliders_slides&action=first&sliderid=' . $slider['id'] . '&slideid=' . $slide['id']); ?>">Set
                                        first slide</a>
                                <?php endif; ?>
                                <?php if ($slide['published']): ?>
                                    <a class="smartslider-icon smartslider-icon-published"
                                       href="<?php echo $this->route('controller=slides&view=sliders_slides&action=unpublish&sliderid=' . $slider['id'] . '&slideid=' . $slide['id']); ?>">Click
                                        to unpublish slide</a>
                                <?php else: ?>
                                    <a class="smartslider-icon smartslider-icon-unpublished"
                                       href="<?php echo $this->route('controller=slides&view=sliders_slides&action=publish&sliderid=' . $slider['id'] . '&slideid=' . $slide['id']); ?>">Click
                                        to publish slide</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php
                    endforeach;
                    ?>
                </ul>

            <?php endif; ?>
        </dd>
    <?php
    endforeach;
    ?>
</dl>
<script type="text/javascript">
njQuery(window).ready(function(){
    var $ = njQuery;
    var stars = $('.smartslider-icon-star, .smartslider-icon-starred'),
        starred = $('.smartslider-icon-starred');
    stars.on('click', function(e){
        var $this = $(this);
        $.ajax({
            url: $this.attr('href')+'&mode=ajax',
            success: function(){
                starred.addClass('smartslider-icon-star').removeClass('smartslider-icon-starred');
                starred = $this.removeClass('smartslider-icon-star').addClass('smartslider-icon-starred')
            }
        }).fail(function() {
            window.location.href = $this.attr('href');
        });
        e.preventDefault();
    });
    
    var publish = $('.smartslider-icon-published, .smartslider-icon-unpublished');
    publish.on('click', function(e){
        var $this = $(this);
        $.ajax({
            url: $this.attr('href')+'&mode=ajax&action='+($this.hasClass('smartslider-icon-published') ? 'unpublish' : 'publish'),
            success: function(){
                if($this.hasClass('smartslider-icon-published')){
                    $this.addClass('smartslider-icon-unpublished').removeClass('smartslider-icon-published');
                }else{
                    $this.addClass('smartslider-icon-published').removeClass('smartslider-icon-unpublished');
                }
            }
        }).fail(function() {
            window.location.href = $this.attr('href')+'&action='+($this.hasClass('smartslider-icon-published') ? 'unpublish' : 'publish');
        });
        e.preventDefault();
    });
    
    
    // Tooltips
    var lis = $('.smartslider-sliders-list > dt, .smartslider-slides-list > li'),
        container = $('#smartslider-admin');
        
    var subcontainer = $('#smartslider-slide-toolbox .smartslider-slide-toolbox-sliders');
    if(subcontainer.length) container = subcontainer;
    
    lis.each(function(){
        $(this).qtip({
            position: {
                container: container,
                my: "left top",
                at: "right center",
                adjust: {
                    y: -6
                }
            },
            content: {
                text: $(this).find('.tooltip-actions') 
            },
            show: {
                solo: true
            },
            hide: {
                fixed: true,
                delay: 400/*,
                event: false*/
            },
            style: {
                classes: 'qtip-nextend',
                tip: {
                    mimic: 'left center',
                    width: 12,
                    height: 6,
                    offset: 10
                }
            }
        });
    });
});
</script>