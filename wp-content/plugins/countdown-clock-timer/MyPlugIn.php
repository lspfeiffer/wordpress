<?php
/*
Plugin Name: Countdown Timer Clock
Plugin URI: www.ipadstopwatch.com
Description: Adds a countdown clock to your site. Works on PC and mobile deivces.
Version: 1.0.1
Author: Luis Perez
Author URI: www.ipadstopwatch.com
*/
 
 
class CountdownWidget extends WP_Widget {
  function CountdownWidget() {
    $widget_ops = array('classname' => 'CountdownWidget', 'description' => 'Adds a count down to your site. Works on PC and mobile deivces.' );
    $this->WP_Widget('CountdownWidget', 'Countdown Clock Timer', $widget_ops);
  }
 
  function form($instance) {
    $instance = wp_parse_args((array)$instance, array('title' => ''));
    $title = $instance['title'];
    $countdownTitle = $instance['countdownTitle'];
    $date = $instance['date'];
    $finalMessage = $instance['finalMessage'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('countdownTitle'); ?>">Countdown Title: <input class="widefat" id="<?php echo $this->get_field_id('countdownTitle'); ?>" name="<?php echo $this->get_field_name('countdownTitle'); ?>" type="text" value="<?php echo attribute_escape($countdownTitle); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('date'); ?>">Date: <input class="widefat" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="text" value="<?php echo attribute_escape($date); ?>" /></label></p>
  <p><em>Example of Date: December 31 2013 12:59 pm</em></p>
  <p><label for="<?php echo $this->get_field_id('finalMessage'); ?>">Final Message: <input class="widefat" id="<?php echo $this->get_field_id('finalMessage'); ?>" name="<?php echo $this->get_field_name('finalMessage'); ?>" type="text" value="<?php echo attribute_escape($finalMessage); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['countdownTitle'] = $new_instance['countdownTitle'];
    $instance['date'] = $new_instance['date'];
    $instance['finalMessage'] = $new_instance['finalMessage'];
    return $instance;
  }
 
  function widget($args, $instance) {
    $date = $instance['date'];
    $countdownTitle = $instance['countdownTitle'];
    $finalMessage = $instance['finalMessage'];
	
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
 ?>
<script>
(function($) {
	$(function() {
		$(".oIPadStopwatchCountdown").each(function() {
			var dateTimeMillis = Date.parse('<?php echo $date; ?>');
			var params = $.param({ date: dateTimeMillis, title: '<?php echo str_replace("'", "\\'", $countdownTitle); ?>', msg: '<?php echo str_replace("'", "\\'", $finalMessage); ?>' });
			var urlEmbed = "http://ipadstopwatch.com/countdown-clock-embed.html#" + params;
		
			var width = $(this).width();
			var height = (width * 157) / 264;
			$(this).height(height);
			$(this).attr("src", urlEmbed);
		});
	});
})(jQuery);
</script>
 
<iframe class="oIPadStopwatchCountdown" src="about:blank" frameborder="0" scrolling="no" style="width:100%;height:140px;"></iframe>
<?php
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("CountdownWidget");') );

function countdown_clock_timer_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'title' => 'Countdown',
		'date' => 'January 1 2000',
		'message' => 'Countdown not set',
		'width' => '157',
	), $atts ) );

	$title = str_replace("'", "\\'", $title);
	$message = str_replace("'", "\\'", $message);
	$height = ($width * 157) / 264;
	
	return "<script>
(function(jQuery) {
	var dateTimeMillis = Date.parse('$date');
	var params = jQuery.param({ date: dateTimeMillis, title: '$title', msg: '$message' });
	var urlEmbed = 'http://ipadstopwatch.com/countdown-clock-embed.html#' + params;

	document.write(\"<iframe src='\"+urlEmbed+\"' frameborder='0' scrolling='no' style='width:{$width}px';height:{$height}px;'></iframe>\");
})(jQuery);
	</script>";
}

add_shortcode('countdown', 'countdown_clock_timer_shortcode' );

?>