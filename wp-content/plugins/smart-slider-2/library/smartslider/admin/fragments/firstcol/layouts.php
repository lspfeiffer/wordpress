<?php
$js = NextendJavascript::getInstance();
if ($this->canDo('core.layout')) :
    $accessLayoutCreate = $this->canDo('layout.create');
    $accessLayoutDelete = $this->canDo('layout.delete');
    ?>
    <?php if ($accessLayoutCreate): ?>
        <div class="smartslider-button-wrap">
            <div class="smartslider-button smartslider-createslider smartslider-button-grey smartslider-button-blue-active smartslider-icon-container <?php echo NextendRequest::getCmd('view') == 'sliders_layouts' && NextendRequest::getCmd('action') == 'create' ? 'active' : ''; ?>">
                <a class="smartslider-button-link" href="<?php echo $this->route('controller=layouts&view=sliders_layouts&action=create'); ?>"><span class="smartslider-icon smartslider-icon-add"></span><?php echo NextendText::_('Create_layout'); ?></a>
            </div>
        </div>
    <?php endif; ?>
    <div style="clear: both;"></div>
    <?php
    $layoutsModel = $this->getModel('layouts');
    $layouts = $layoutsModel->getLayouts();
    $layoutid = NextendRequest::getInt('layoutid', 0);
    ?>
    <dl class="smartslider-list smartslider-sliders-list smartslider-layouts-list">
        <?php
        $i = 0;
        foreach ($layouts AS $layout):
            $c = $i % 2 ? 'even' : 'odd';
            $i++;
            $active = $layoutid == $layout['id'];
            ?>
            <dt class="<?php echo $c; ?> smartslider-button-blue-active smartslider-icon-container <?php echo $active ? 'subactive' : ''; ?> <?php echo $active && NextendRequest::getCmd('controller') == 'layouts' ? 'active' : ''; ?>">
                <a class="smartslider-button-link" href="<?php echo $this->route('controller=layouts&view=sliders_layouts&action=edit&layoutid=' . $layout['id']); ?>"><?php echo $layout['title']; ?></a>
                <?php if ($accessLayoutDelete): ?>
                    <a onclick="return confirm('<?php echo NextendText::_('Are_you_sure_that_you_want_to_delete_the_layout'); ?>')" class="smartslider-icon smartslider-icon-trash" href="<?php echo $this->route('controller=layouts&view=sliders_layouts&action=delete&layoutid=' . $layout['id']); ?>"><?php echo NextendText::_('Delete_layout'); ?></a>
                <?php endif; ?>
            </dt>
            <dd style="display: none;"></dd>
        <?php
        endforeach;
        ?>
    </dl>
<?php
endif;
