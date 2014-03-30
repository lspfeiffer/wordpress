<?php

class NextendDatabase extends NextendDatabaseAbstract {

    var $db = null;
    
    var $query = '';
    
    var $nameQuote = '`';

    function NextendDatabase() {
        global $wpdb;
        $this->db = $wpdb;
        $wpdb->show_errors();
    }

    function setQuery($query) {
        $this->query = str_replace('#__', $this->db->prefix, $query);
    }

    function query() {
        $this->db->query($this->query);
    }

    function loadAssoc() {
        return $this->db->get_row($this->query, ARRAY_A);
    }

    function loadAssocList($key = null) {
        $rs = $this->db->get_results($this->query, ARRAY_A);
        if(!$key) return $rs;
        
        $re = array();
        foreach($rs AS $r){
          $re[$r[$key]] = $r;
        }
        return $re;
    }
    
    function escape($s){
        return esc_sql($s);
    }

    function quote($s) {
        return '\''.(esc_sql($s)).'\'';
    }

    function quoteName($name) {
        if (strpos($name, '.') !== false) {
            return $name;
        }
        else {
            $q = $this->nameQuote;
            if (strlen($q) == 1) {
                return $q.$name.$q;
            } else {
                return $q{0}.$name.$q{1};
            }
        }
    }
    
    function insertid(){
        return $this->db->insert_id;
    }

}

?>
