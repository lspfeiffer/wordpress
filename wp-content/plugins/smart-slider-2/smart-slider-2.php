<?php
/*
Plugin Name: Smart Slider 2
Plugin URI: http://nextendweb.com/
Description: The perfect all-in-one responsive slider solution for WordPress.
Version: 2.3.7
Author: Nextend
Author URI: http://www.nextendweb.com
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/*  Copyright 2012  Roland Soos - Nextend  (email : roland@nextendweb.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 
define('NEXTEND_SMART_SLIDER2_BASE', basename(__FILE__,'.php'));
define('NEXTEND_SMART_SLIDER2', dirname(__FILE__) . DIRECTORY_SEPARATOR );
define('NEXTEND_SMART_SLIDER2_ASSETS', NEXTEND_SMART_SLIDER2 . 'library' . DIRECTORY_SEPARATOR . 'smartslider' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR );

$nextend_smart_slider2_api_url = 'http://www.nextendweb.com/update/wordpress/';
$nextend_smart_slider2_plugin_slug = basename(dirname(__FILE__));

register_activation_hook( __FILE__, 'nextend_smart_slider2_activation');
function nextend_smart_slider2_activation($network_wide){
    include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install.php');
}

add_action( 'admin_menu', 'nextend_smart_slider2_menu_page' );
function nextend_smart_slider2_menu_page(){
    add_menu_page( 'Smart Slider 2', 'Sliders', 'manage_options', 'nextend-smart-slider2', 'nextend_smart_slider2', 'dashicons-format-gallery');
}

function nextend_smart_slider2(){
    nextendimportsmartslider2('nextend.smartslider.admin.controller');
    
    $controller = new NextendSmartsliderAdminController('com_smartslider2');
    $controller->initBase();
    $controller->run();
}

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'update.php');

add_action( 'plugins_loaded', 'nextend_smart_slider2_load');
function nextend_smart_slider2_load(){

    if (!defined('NEXTENDLIBRARY') && file_exists(dirname(__FILE__).'/nextend/wp-library.php')) {
        require_once(dirname(__FILE__).'/nextend/wp-library.php');
    }
    
    if(defined('NEXTENDLIBRARY')){
        add_action( 'wp_enqueue_scripts', 'nextend_ss2_jquery');
        
        if(is_admin()){
            require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'admin.php');
        }
        
        require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'shortcode.php');
        
        require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'widget.php');
    }
    
    require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tinymce' . DIRECTORY_SEPARATOR . 'shortcode.php');
}

add_action( 'nextend_load', 'nextend_load_ss2');
function nextend_load_ss2(){
    require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'plugins/loadplugin.php');
}

function nextend_ss2_jquery(){
    wp_enqueue_script('jquery');
}