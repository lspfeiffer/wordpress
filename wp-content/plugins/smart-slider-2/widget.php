<?php

class NextendSmartSlider2Widget extends WP_Widget {

    function NextendSmartSlider2Widget() {
        $widget_ops = array('classname' => 'NextendSmartSlider2Widget', 'description' => 'Displays a Smart Slider');
        $this->WP_Widget('NextendSmartSlider2Widget', 'Nextend Smart Slider 2', $widget_ops);
    }

    function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('title' => '', 'smartslider2tablet' => -1, 'smartslider2phone' => -1));
        $title = $instance['title'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title: 
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('smartslider2'); ?>">
                Smart Slider: 
                <select class="widefat" id="<?php echo $this->get_field_id('smartslider2'); ?>" name="<?php echo $this->get_field_name('smartslider2'); ?>">
                    <?php
                    $smartslider2 = $instance['smartslider2'];
                    
                    $res = $wpdb->get_results( 'SELECT id, title FROM '.$wpdb->prefix.'nextend_smartslider_sliders' );
                    foreach ($res AS $r) {
                        ?>
                        <option <?php if ($r->id == $smartslider2) { ?>selected="selected" <?php } ?>value="<?php echo $r->id; ?>"><?php echo $r->title; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('smartslider2tablet'); ?>">
                Tablet: 
                <select class="widefat" id="<?php echo $this->get_field_id('smartslider2tablet'); ?>" name="<?php echo $this->get_field_name('smartslider2tablet'); ?>">
                    <?php
                    $smartslider2 = $instance['smartslider2tablet'];
                    
                    $res = $wpdb->get_results( 'SELECT id, title FROM '.$wpdb->prefix.'nextend_smartslider_sliders' );
                    ?>
                    <option <?php if (-1 == $smartslider2) { ?>selected="selected" <?php } ?>value="-1">Display default slider</option>
                    <option <?php if (0 == $smartslider2) { ?>selected="selected" <?php } ?>value="0">Display nothing</option>
                    <?php
                    foreach ($res AS $r) {
                        ?>
                        <option <?php if ($r->id == $smartslider2) { ?>selected="selected" <?php } ?>value="<?php echo $r->id; ?>"><?php echo $r->title; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('smartslider2phone'); ?>">
                Phone: 
                <select class="widefat" id="<?php echo $this->get_field_id('smartslider2phone'); ?>" name="<?php echo $this->get_field_name('smartslider2phone'); ?>">
                    <?php
                    $smartslider2 = $instance['smartslider2phone'];
                    $res = $wpdb->get_results( 'SELECT id, title FROM '.$wpdb->prefix.'nextend_smartslider_sliders' );
                    ?>
                    <option <?php if (-1 == $smartslider2) { ?>selected="selected" <?php } ?>value="-1">Display default slider</option>
                    <option <?php if (0 == $smartslider2) { ?>selected="selected" <?php } ?>value="0">Display nothing</option>
                    <?php
                    foreach ($res AS $r) {
                        ?>
                        <option <?php if ($r->id == $smartslider2) { ?>selected="selected" <?php } ?>value="<?php echo $r->id; ?>"><?php echo $r->title; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
        </p>
        <p>You can create Sliders in the left sidebar.</p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['smartslider2'] = $new_instance['smartslider2'];
        $instance['smartslider2tablet'] = $new_instance['smartslider2tablet'];
        $instance['smartslider2phone'] = $new_instance['smartslider2phone'];
        return $instance;
    }

    function widget($args, $instance) {
        $instance = array_merge(array(
            'id' => md5(time()),
            'smartslider2' => 0,
            'smartslider2tablet' => -1,
            'smartslider2phone' => -1
          ), $instance);
        
        $slider = intval($instance['smartslider2']);
        $tablet = intval($instance['smartslider2tablet']);
        $phone = intval($instance['smartslider2phone']);
        
        $loadCheck = false;
        if($tablet >= 0 || $phone >= 0) $loadCheck = true;
        
        if($loadCheck){
            nextendimport('nextend.externals.mobiledetect');
            $detect = new Mobile_Detect();
            $istablet = $detect->isTablet();
            $ismobile = !$istablet && $detect->isMobile();
            
            if($istablet){
                if($tablet == 0){
                    return '';
                }
                if($tablet > 0) $slider = $tablet;
            }
            if($ismobile){
                if($phone == 0){
                    return '';
                }
                if($phone > 0) $slider = $phone;
            }
            
        }

        $title = apply_filters( 'widget_title', $instance['title'] );

    		echo $args['before_widget'];
    		if ( ! empty( $title ) )
    			echo $args['before_title'] . $title . $args['after_title'];

        $params = array();
        
        nextendimportsmartslider2('nextend.smartslider.slidercache');
        nextendimportsmartslider2('nextend.smartslider.wordpress.slider');
        
        new NextendSliderCache(new NextendSliderWordpress(intval($slider), $params, dirname(__FILE__)));

        echo $args['after_widget'];
    }

}
add_action('widgets_init', create_function('', 'return register_widget("NextendSmartSlider2Widget");'));
