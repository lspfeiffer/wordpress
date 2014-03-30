<?php

nextendimport('nextend.database.database');

class NextendSmartSliderStorage {

    static $cache = array();

    static function get($key, $default = null) {
        if (!array_key_exists($key, self::$cache)) {
            $db = NextendDatabase::getInstance();
            $db->setQuery('SELECT value FROM #__nextend_smartslider_storage WHERE ' . $db->quoteName('key') . ' = ' . $db->quote($key));
            $row = $db->loadAssoc();
            if($row !== null) $row = $row['value'];
            self::$cache[$key] = $row;
        }
        if (self::$cache[$key] === null)
            return $default;
        return self::$cache[$key];
    }

    static function set($key, $value) {
        $db = NextendDatabase::getInstance();
        $sql = '';
        if(self::get($key) === null){
            $db->insert('#__nextend_smartslider_storage', array(
                'key' => $key,
                'value' => $value
            ));
        }else{
            $db->update('#__nextend_smartslider_storage', array(
                'value' => $value
            ), $db->quoteName('key') . " = ".$db->quote($key));
        }
        self::$cache[$key] = $value;
    }

    static function delete($key) {
        $db = NextendDatabase::getInstance();
        $sql = '';
        if(self::get($key) !== null){
            $sql = "DELETE FROM #__nextend_smartslider_storage WHERE " . $db->quoteName('key') . " = ".$db->quote($key);
            $db->setQuery($sql);
            $db->query();
            unset(self::$cache[$key]);
        }
    }

}