<?php

nextendimportsmartslider2('nextend.smartslider.storage');

class NextendSmartSliderSettings {

    static $settings = null;

    static function getAll() {
        if (self::$settings === null) {
            self::$settings = json_decode(NextendSmartSliderStorage::get('settings'), true);
            if (self::$settings === null)
                self::$settings = array();
        }
        return self::$settings;
    }

    static function get($key, $default = null) {
        if (self::$settings === null)
            self::getAll();
        if (!array_key_exists($key, self::$settings))
            return $default;
        return self::$settings[$key];
    }

}

class NextendSmartSliderLayoutSettings {

    static $settings = null;

    static function getAll() {
        if (self::$settings === null) {
            self::$settings = json_decode(NextendSmartSliderStorage::get('layout'), true);
            if (self::$settings === null)
                self::$settings = array();
        }
        return self::$settings;
    }

    static function get($key, $default = null) {
        if (self::$settings === null)
            self::getAll();
        if (!array_key_exists($key, self::$settings))
            return $default;
        return self::$settings[$key];
    }

}

class NextendSmartSliderFontSettings {

    static $settings = array();

    static function getAll($id = 0) {
        if (!isset(self::$settings[$id])) {
            self::$settings[$id] = json_decode(NextendSmartSliderStorage::get('font'.($id ? $id : '')), true);
            if (self::$settings[$id] === null)
                self::$settings[$id] = self::getAll();
        }
        return self::$settings[$id];
    }

    static function get($key, $default = null, $id = 0) {
        if (!isset(self::$settings[$id]))
            self::getAll($id);
        if (!array_key_exists($key, self::$settings[$id]))
            return $default;
        return self::$settings[$id][$key];
    }
    
    static function initAdminFonts($id = 0){
        $data = self::getAll($id);
        $GLOBALS['nextendfontmatrix'] = array();
        if (is_array($data)) {
            foreach ($data AS $k => $v) {
                preg_match('/sliderfont(custom)?([0-9]*)$/', $k, $matches);
                if (count($matches)) {
                    $GLOBALS['nextendfontmatrix'][$matches[0]] = $data[$matches[0].'customlabel'];
                }
            }
        }
    }
}

class NextendSmartSliderJoomlaSettings {

    static $settings = null;

    static function getAll() {
        if (self::$settings === null) {
            self::$settings = json_decode(NextendSmartSliderStorage::get('joomla'), true);
            if (self::$settings === null)
                self::$settings = array();
        }
        return self::$settings;
    }

    static function get($key, $default = null) {
        if (self::$settings === null)
            self::getAll();
        if (!array_key_exists($key, self::$settings))
            return $default;
        return self::$settings[$key];
    }

}