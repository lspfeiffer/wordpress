<?php
class NextendSliderItems {
    
    static $i = array();

    var $id = 0;
    
    var $items = null;
    
    var $admin = false;

    function NextendSliderItems($id, $admin) {
    
        $this->id = $id;
        $this->admin = $admin;
        $this->items = array();
        if(!isset(self::$i[$id])) self::$i[$id] = 0;
        
        NextendPlugin::callPlugin('nextendslideritem', 'onNextendSliderItemShortcode', array(&$this->items));
    }
    
    function render($slider){
        return preg_replace_callback("/\[([a-zA-Z]+) values=\"(.*?)\"]/", array($this, 'makeItem'), $slider);
    }
    
    function makeItem($args){
        if(isset($this->items[$args[1]])){
            $data = new NextendData();
            $data->loadJson(base64_decode($args[2]));
            if($data->_data != null){
                ++self::$i[$this->id];
                if($this->admin){
                    return $this->items[$args[1]]->renderAdmin($data, $this->id.'item'.self::$i[$this->id], $this->id);
                }
                return $this->items[$args[1]]->render($data, $this->id.'item'.self::$i[$this->id], $this->id);
            }
        }
        return '';
    }
}