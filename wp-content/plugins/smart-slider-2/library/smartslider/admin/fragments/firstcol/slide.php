<?php
$css = NextendCss::getInstance();
$css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/firstcolslidetoolbox.css');
?>

<div id="smartslider-slide-toolbox"
     class="smartslider-slide-toolbox-slide-active smartslider-slide-layout-default-active">
    <!--
    <div class="smartslider-greybar smartslider-button-grey">
        <div class="smartslider-toolbar-list smartslider-toolbar-options smartslider-button-grey first">
            <div><?php echo NextendText::_('List'); ?></div>
        </div>
        <div class="smartslider-toolbar-edit smartslider-toolbar-options smartslider-button-grey last">
            <div><?php echo NextendText::_('Edit'); ?></div>
        </div>
    </div>
    -->
    <div class="smartslider-slide-toolbox-pane nextend-clearfix">
        <div class="smartslider-slide-toolbox-sliders">
            <?php
            if (NextendRequest::getCmd('controller') == 'layouts'):
                $this->loadFragment('firstcol/layouts');
            else:
                $this->loadFragment('firstcol/sliders');
            endif;
            ?>
        </div>
        <script type="text/javascript">
            njQuery(window).ready(function($){
                var pane = $('.smartslider-slide-toolbox-pane .smartslider-slide-toolbox-sliders'),
                    timeout = null;
                pane.on('mouseenter', function(){
                    if(timeout) clearTimeout(timeout);
                    timeout = setTimeout(function(){
                        pane.addClass('active');
                        
                        pane.stop().animate({
                            height: pane.prop('scrollHeight')
                        },{
                            complete: function(){
                                pane.css('overflow', 'auto');
                                $(window).trigger('resize');
                            }
                        });
                    }, 400);
                }).on('mouseleave', function(){
                    if(timeout) clearTimeout(timeout);
                    timeout = setTimeout(function(){
                        pane.stop().animate({
                            height: 48
                        },{
                            complete: function(){
                                pane.css('overflow', 'hidden');
                                $(window).trigger('resize');
                            }
                        });
                        
                        pane.removeClass('active');
                    }, 400);
                });
            });
        </script>
        <!--<div class="smartslider-slide-toolbox-pane-inner nextend-clearfix">-->
            <div class="smartslider-slide-toolbox-slide">
                <div class="smartslider-slide-views nextend-clearfix">
                    <div
                        class="active smartslider-slide-view smartslider-slide-views-layout smartslider-button-grey smartslider-button-blue-active">
                        <div class="smartslider-border"><?php echo NextendText::_('Layout'); ?></div>
                    </div>
                    <div
                        class="active smartslider-slide-view smartslider-slide-views-layer smartslider-button-grey smartslider-button-blue-active">
                        <div class="smartslider-border"><?php echo NextendText::_('Layer'); ?></div>
                    </div>
                    <div
                        class="smartslider-slide-view smartslider-slide-views-item smartslider-button-grey smartslider-button-blue-active smartslider-button-purple-active">
                        <div class="smartslider-border"><?php echo NextendText::_('Item'); ?></div>
                    </div>
                </div>

                <div class="smartslider-slide-views-pane-inner nextend-clearfix">
                    <div id="smartslider-slide-toolbox-layout" class="nextend-clearfix smartslider-slide-toolbox-view">
                        <?php if ($this->canDo('layout.create')): ?>
                            <div class="smartslider-button-wrap">
                                <div
                                    class="smartslider-button smartslider-savelayout smartslider-button-grey smartslider-button-blue-active smartslider-icon-container ">
                                    <a href="#" class="smartslider-button-link"><span
                                            class="smartslider-icon smartslider-icon-add"></span><?php echo NextendText::_('Save_as_new_layout'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="smartslider-greybar smartslider-button-grey">
                            <div
                                class="smartslider-layout-default smartslider-toolbar-options smartslider-button-grey first">
                                <div><?php echo NextendText::_('Default_layouts'); ?></div>
                            </div>
                            <div
                                class="smartslider-layout-custom smartslider-toolbar-options smartslider-button-grey last">
                                <div><?php echo NextendText::_('Custom_layouts'); ?></div>
                            </div>
                        </div>
                        <div class="smartslider-slide-layout-pane-inner nextend-clearfix">
                            <?php
                            nextendimportsmartslider2('nextend.smartslider.items');
                            $items = new NextendSliderItems('nextend-smart-slider-0', true);
                            
                            $layoutsModel = $this->getModel('layouts');
                            ?>
                            <div class="smartslider-slide-layout-default">
                                <?php
                                $layouts = $layoutsModel->getCoreLayouts();
                                ?>
                                <?php
                                foreach ($layouts AS $layout):
                                    ?>
                                    <div class="smartslider-core-layout <?php echo $layout['class']; ?>">
                                        <a class="smartslider-load-layout" href="#"></a>

                                        <div class="smartslider-layout-container">
                                            <?php echo $items->render($layout['slide']); ?>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                                ?>
                            </div>
                            <div class="smartslider-slide-layout-custom">
                                <?php
                                $layouts = $layoutsModel->getLayouts();
                                ?>
                                <dl class="smartslider-list smartslider-sliders-list">
                                    <?php
                                    $i = 0;
                                    foreach ($layouts AS $layout):
                                        $c = $i % 2 ? 'even' : 'odd';
                                        $i++;
                                        ?>
                                        <dt class="<?php echo $c; ?> smartslider-button-blue-active smartslider-icon-container">
                                            <a class="smartslider-button-link smartslider-load-layout"
                                               href="#"><?php echo $layout['title']; ?></a>
                                        <div class="smartslider-layout-container">
                                            <?php echo $items->render($layout['slide']); ?>
                                        </div>
                                        </dt>
                                    <?php
                                    endforeach;
                                    ?>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div id="smartslider-slide-toolbox-layer" class="active nextend-clearfix smartslider-slide-toolbox-view">
                        <div class="smartslider-button-wrap">
                            <div
                                class="smartslider-button smartslider-createlayer smartslider-button-grey smartslider-icon-container">
                                <span class="smartslider-icon smartslider-icon-add"></span><?php echo NextendText::_('Add_new_layer'); ?>
                            </div>
                        </div>
                        <div style="clear: both;"></div>

                        <?php
                        $layerModel = $this->getModel('layer');
                        $layerModel->renderForm();
                        ?>
                    </div>
                    <div id="smartslider-slide-toolbox-item" class="nextend-clearfix smartslider-slide-toolbox-view">
                        <script type="text/javascript">
                            window.ssitemmarker = true;
                        </script>
                        <dl class="smartslider-list smartslider-items-list">
                            <dt style="display: none;"
                                class="even smartslider-button-blue-active smartslider-icon-container subactive ">
                                <a href="#" class="smartslider-button-link" onclick="return false;"><?php echo NextendText::_('Add_item'); ?></a>
                                <span class="smartslider-arrowdown"></span>
                            </dt>
                            <dd class="active">
                                <?php
                                $items = array();
                                NextendPlugin::callPlugin('nextendslideritem', 'onNextendSliderItemList', array(&$items));
                                ?>
                                <div id="draggableitems" class="nextend-clearfix">
                                    <?php foreach ($items AS $type => $item): ?>
                                        <div
                                            class="smart-slider-item-container smartslider-button-grey"><?php echo $item[0]; ?>
                                            <div style="display: none;" class="smart-slider-items"
                                                 data-item="<?php echo $type; ?>"
                                                 data-itemvalues='<?php echo $item[3]; ?>'>
                                                <?php echo str_replace('{{id}}', 'nextend-smart-slider-0', $item[2]); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </dd>
                        </dl>
                        <script type="text/javascript">
                            delete window.ssitemmarker;
                        </script>
                        <?php
                        $itemModel = $this->getModel('item');

                        $itemModel->renderHelperForm();
                        ?>
                        <?php
                        foreach ($items AS $type => $item):
                            ?>
                            <div id="smartslider-slide-toolbox-item-type-<?php echo $type; ?>" style="display:none;"
                                 data-itemtemplate="<?php echo htmlspecialchars($item[1]); ?>">

                                <?php
                                $itemModel->renderForm($type, $item);
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <!--</div>-->
    </div>
</div>