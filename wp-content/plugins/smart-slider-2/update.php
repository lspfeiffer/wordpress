<?php
add_action('admin_menu', 'nextend_smart_slider2_update_page');

function nextend_smart_slider2_update_page() {
	add_submenu_page('nextend-smart-slider2', 'Nextend Smart Slider 2 License', 'Get Full', 'manage_options', __FILE__, 'nextend_smart_slider2_settings_page');
}

function nextend_smart_slider2_settings_page() {
    wp_redirect( admin_url('admin.php?page=nextend-smart-slider2&controller=sliders&view=sliders_full&action=full'), 301 );
    exit;
}
