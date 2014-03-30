<?php
$action = NextendRequest::getCmd('action', 'default');
$settings = array(
    array('id' => 'default', 'title' => NextendText::_('General_settings')),
    array('id' => 'layout', 'title' => NextendText::_('Layout_settings'))
);
if(nextendIsJoomla()){
    $settings[] = array('id' => 'joomla', 'title' => NextendText::_('Joomla_settings'));
}
NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendGeneratorConfigurationList', array(&$settings));

$settings[] = array('id' => 'font', 'title' => NextendText::_('Global_font'));

?>
<dl class="smartslider-list smartslider-sliders-list">
    <?php
    $i = 0;
    foreach ($settings AS $setting):
        $c = $i % 2 ? 'even' : 'odd';
        $i++;
        $active = $action == $setting['id'];
        if($action == 'font' && NextendRequest::getInt('sliderid')) $active = false;
        ?>
        <dt class="<?php echo $c; ?> smartslider-button-blue-active smartslider-icon-container <?php echo $active ? 'active' : ''; ?>">
        <a class="smartslider-button-link" href="<?php echo $this->route('controller=settings&view=sliders_settings&action=' . $setting['id']); ?>"><?php echo $setting['title']; ?></a>
        </dt>
        <?php
        if($setting['id'] == 'font'){
            ?>
            <dd class="active">
                <ul class="smartslider-slides-list smartslider-sliders-list">
                <?php
                $slidersModel = $this->getModel('sliders');
                $sliders = $slidersModel->getSliders();
                $sliderid = NextendRequest::getInt('sliderid');
                foreach($sliders AS $slider){
                    $activeslider = $slider['id'] == $sliderid;
                      ?>
                      <li class="smartslider-slider smartslider-slide smartslider-icon-container smartslider-button-blue-active <?php echo $activeslider ? 'active' : ''; ?>">
                          <a class="smartslider-button-link" href="<?php echo $this->route('controller=settings&view=sliders_settings&action=font&sliderid=' . $slider['id'] ); ?>">
                             <?php echo $slider['title']; ?>
                          </a>
                          <div class="tooltip-actions" style="display: none;">
                              <ul class="sidebar-tooltip-menu">
                                  <li class="smartslider-icon-container"><a href="<?php echo $this->route('controller=settings&view=sliders_settings&action=clearfonts&sliderid=' . $slider['id'] ); ?>"><span class="smartslider-qtip-icon subdelete"></span> <?php echo NextendText::_('Reset_to_global_fonts'); ?></a></li>
                              </ul>
                          </div>
                      </li>
                  <?php
                }
                ?>
                </ul>
                <script type="text/javascript">
                njQuery(window).ready(function(){
                    var lis = njQuery('.smartslider-sliders-list > li');
                    
                    lis.each(function(){
                        njQuery(this).qtip({
                            position: {
                                container: njQuery('#smartslider-admin'),
                                /*my: "left top",*/
                                my: "left center",
                                at: "right center"/*,
                                adjust: {
                                    y: -6
                                }*/
                            },
                            content: {
                                text: njQuery(this).find('.tooltip-actions') 
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
                                classes: 'qtip-nextend'/*,
                                tip: {
                                    mimic: 'left center',
                                    width: 12,
                                    height: 6,
                                    offset: 10
                                }*/
                            }
                        });
                    });
                });
                </script>
            </dd>
            <?php
        }else{
            ?>
            <dd style="display: none;"></dd>
            <?php
        }
        ?>
        <?php
    endforeach;
    ?>
</dl>