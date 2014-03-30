<?php

if(!defined('WP_ADMIN') || !is_admin()) return;

global $wpdb;

function NextendSplitSql($sql){
  $start = 0;
  $open = false;
  $char = '';
  $end = strlen($sql);
  $queries = array();

  for ($i = 0; $i < $end; $i++){
      $current = substr($sql,$i,1);
      if (($current == '"' || $current == '\'')) {
          $n = 2;

          while (substr($sql,$i - $n + 1, 1) == '\\' && $n < $i){
              $n ++;
          }

          if ($n%2==0) {
              if ($open) {
                  if ($current == $char) {
                      $open = false;
                      $char = '';
                  }
              } else {
                  $open = true;
                  $char = $current;
              }
          }
      }

      if (($current == ';' && !$open)|| $i == $end - 1) {
          $queries[] = substr($sql, $start, ($i - $start + 1));
          $start = $i + 1;
      }
  }

  return $queries;
}

if(defined('MULTISITE') && MULTISITE){
    if($network_wide){
        $blogs = function_exists('wp_get_sites') ? wp_get_sites(array('network_id' => $wpdb->siteid)) : get_blog_list( 0, 'all' );
        foreach($blogs AS $blog){
            $prefix = $wpdb->get_blog_prefix($blog['blog_id']);
            $table = $prefix.'nextend_smartslider_sliders';
            if($wpdb->get_var("SHOW TABLES LIKE '".$table."'") != $table) {
                $query = str_replace('#__', $prefix, file_get_contents(dirname(__FILE__).'/install.sql'));
                $queries = NextendSplitSql($query);
                foreach($queries AS $query){
                    if(trim($query) != '') $wpdb->query($query);
                }
            }
        }
    }else{
        global $blog_id;
        $prefix = $wpdb->get_blog_prefix($blog_id);
        $table = $prefix.'nextend_smartslider_sliders';
        if($wpdb->get_var("SHOW TABLES LIKE '".$table."'") != $table) {
            $query = str_replace('#__', $prefix, file_get_contents(dirname(__FILE__).'/install.sql'));
            $queries = NextendSplitSql($query);
            foreach($queries AS $query){
                if(trim($query) != '') $wpdb->query($query);
            }
        }
    }
}else{
    $table = $wpdb->prefix.'nextend_smartslider_sliders';
    if($wpdb->get_var("SHOW TABLES LIKE '".$table."'") != $table) {
        $query = str_replace('#__', $wpdb->prefix, file_get_contents(dirname(__FILE__).'/install.sql'));
        $queries = NextendSplitSql($query);
        foreach($queries AS $query){
            if(trim($query) != '') $wpdb->query($query);
        }
    }
}

include( dirname(__FILE__).'/installfull.php' );
