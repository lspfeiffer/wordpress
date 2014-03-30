<?php

global $ss2ButtonsActivated;

$ss2ButtonsActivated = false;

function nextendsmartslider2_register_button( $buttons ) {
   array_push( $buttons, "|", "nextendsmartslider2" );
   return $buttons;
}

function nextendsmartslider2_add_plugin( $plugin_array ) {
   $plugin_array['nextendsmartslider2'] = plugin_dir_url(__FILE__) . 'shortcode.js';
   return $plugin_array;
}

function nextendsmartslider2_button_button() {
    global $ss2ButtonsActivated;
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }
    if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post-new.php', 'page-new.php', 'post.php', 'page.php' ) ) ) {
    
        $ss2ButtonsActivated = true;
        
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('jquery-ui', plugin_dir_url(__FILE__) . 'jquery-ui.min.css');
        wp_enqueue_style('jquery-ui-core', plugin_dir_url(__FILE__) . 'jquery.ui.core.min.css');
        wp_enqueue_style('jquery-ui-theme', plugin_dir_url(__FILE__) . 'jquery.ui.theme.min.css');
        wp_enqueue_style('jquery-ui-dialog', plugin_dir_url(__FILE__) . 'jquery.ui.dialog.min.css');
        wp_enqueue_style('jquery-ui-button', plugin_dir_url(__FILE__) . 'jquery.ui.button.min.css');
        wp_enqueue_style('jquery-ui-nextend', plugin_dir_url(__FILE__) . 'jquery.ui.nextend.css');
        
        if ( get_user_option('rich_editing') == 'true' ) {
            add_filter( 'mce_external_plugins', 'nextendsmartslider2_add_plugin' );
            add_filter( 'mce_buttons', 'nextendsmartslider2_register_button' );
        }
    }
}

add_action( 'admin_init', 'nextendsmartslider2_button_button');

function nextendsmartslider2_button_dialog(){
    global $ss2ButtonsActivated, $wpdb;
    if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post-new.php', 'page-new.php', 'post.php', 'page.php' ) ) ) {
        if ( $ss2ButtonsActivated ) {
          
          $wpdb->nextend_smartslider_slides = $wpdb->base_prefix.'nextend_smartslider_slides';
          $wpdb->nextend_smartslider_sliders = $wpdb->base_prefix.'nextend_smartslider_sliders';
          
          $query = 'SELECT a.title, a.id FROM '.$wpdb->nextend_smartslider_sliders.' AS a';
          $smartsliders = $wpdb->get_results($query, ARRAY_A);
          ?>
          <div id='nextend-smart-sliders-modal' title='Select a Slider' style="display:none;">Please choose a slider from the following list:</div>
          
          <script>
          (function(){
              var $el = jQuery('#nextend-smart-sliders-modal'),
                  modalInited = false,
                  ED = null;
                  
              function addToEditor(id){
                  ED.execCommand('mceInsertContent', false, '<div>[smartslider2 slider="'+id+'"]</div>');
              }
              
              function NextendSmartSliderModalInit(){
                  $el.dialog({ 
                      modal: true, 
                      draggable: false, 
                      resizable: false,
                      dialogClass: 'nextend-shortcode',
                      width: 900,
                      'buttons' : {
                          <?php
                          if(count($smartsliders)){
                              foreach($smartsliders AS $slider){
                                  ?>
                                  "<?php echo $slider['title']; ?>": function() {
                                      jQuery(this).dialog('close');
                                      addToEditor(<?php echo intval($slider['id']); ?> );
                                  },
                                  <?php
                              }
                          }
                          ?>
                      }
                  });
                  modalInited = true;
              }
              
              window.NextendSmartSliderModal = function(ed){
                  if(!modalInited){
                      NextendSmartSliderModalInit();
                  }else{
                      $el.dialog('open');
                  }
                  ED = ed;
              };
          })();
          </script>
          
          <?php 
        }
    }
}

add_action( 'admin_footer', 'nextendsmartslider2_button_dialog' );
