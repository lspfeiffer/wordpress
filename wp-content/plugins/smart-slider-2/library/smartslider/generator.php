<?php
class NextendSmartsliderGenerator {

    var $_group = null;

    var $_list = null;

    var $_generator;

    var $_slide;
    
    var $_sliderid;

    var $_datasource;

    var $_slidePointer;

    var $_tidy = false;

    function NextendSmartsliderGenerator($generator, $slide, $sliderid) {

        $this->_generator = $generator;
        $this->_slide = $slide;
        $this->_sliderid = $sliderid;

        if (class_exists('Tidy', false)) {
            $this->_tidy = true;
        }
        $this->_tidyInputEncoding = NextendSmartSliderSettings::get('tidy-input-encoding', 'utf8');
        $this->_tidyOutputEncoding = NextendSmartSliderSettings::get('tidy-output-encoding', 'utf8');

        $this->_generateslides = NextendParse::parse($this->_generator->get('generateslides'));
        $this->_number = intval($this->_generateslides[0]);

        $this->_generatorgroup = intval($this->_generator->get('generatorgroup', 1));
        if ($this->_generatorgroup < 1) $this->_generatorgroup = 1;

        $this->loadList();

    }

    function initDatasource($source, $forcedGeneratorObj = false) {
        $tmp = $this->_generator->toArray();
        unset($tmp['generateslides']);
        $hash = md5($this->_number * $this->_generatorgroup.json_encode($tmp));
        $recache = false;
        $cached = json_decode(NextendSmartSliderStorage::get('generator'.$this->_sliderid), true);
        
        if(is_array($cached)){
            if($cached['hash'] != $hash){
                $recache = true; 
            }else if($cached['time'] < time()-$this->_generator->get('cachetime', '24')*60*60){
                $recache = true; 
            }
        }else{
            $recache = true; 
        }
        if($recache){
            $v = explode('_', $source);
            require_once($this->_list[$v[0]][$source][1] . 'generator.php');
    
            $class = 'NextendGenerator' . $source;
            $generator = new $class($this->_generator);
            
            ob_start();
            $this->_datasource = $generator->getData($this->_number * $this->_generatorgroup);
            $message = ob_get_clean();
            if(count($this->_datasource) == 0 && trim($message) != '' && count($cached['data']) ){
                $this->_datasource = $cached['data'];
            }
            echo $message;
            
            $cached = array(
                'time' => time(),
                'hash' => $hash,
                'data' => $this->_datasource
            );
            NextendSmartSliderStorage::set('generator'.$this->_sliderid, json_encode($cached));
            if($forcedGeneratorObj) return $generator;
        }else{
            $this->_datasource = $cached['data'];
            if($forcedGeneratorObj){
                $v = explode('_', $source);
                require_once($this->_list[$v[0]][$source][1] . 'generator.php');
        
                $class = 'NextendGenerator' . $source;
                $generator = new $class($this->_generator);
                
                return $generator;
            }
        }
    }

    function generateSlides($sliderid, $static = true) {
        $slides = array();
        $source = $this->_generator->get('source', '');
        if ($source) {
            $this->initDatasource($source);

            $this->_slidePointer = 0;

            for ($i = 0; $i < $this->_number && $i < floor(count($this->_datasource) / $this->_generatorgroup); $i++) {
                $slides[$i] = array_merge(array(
                    'id' => $sliderid * 10000 + $i,
                    'title' => 'Title',
                    'slider' => $sliderid,
                    'publish_up' => '0000-00-00 00:00:00',
                    'publish_down' => '0000-00-00 00:00:00',
                    'published' => 1,
                    'first' => 0,
                    'slide' => '',
                    'params' => '',
                    'thumbnail' => '',
                    'description' => 'Description',
                    'ordering' => $i
                ), (array)$this->createSlide($i, $this->_slide->_data, $static));
            }
        }
        return $slides;
    }

    function createSlide($i, $slidedata, $static = true) {
        $slide = array();
        if ($static) {
            foreach ($slidedata AS $k => $v) {
                $slidedata[$k] = $slide[$k] = $this->replaceText(
                    preg_replace_callback('/(data\-itemvalues=")([^"]*)/S', array($this, 'onAttributeData'), $v)
                );
            }
        } else {
            foreach ($slidedata AS $k => $v) {
                $slidedata[$k] = $slide[$k] = $v;
            }
        }
        
        unset($slidedata['title']);
        unset($slidedata['slide']);
        unset($slidedata['description']);
        unset($slidedata['thumbnail']);
        unset($slidedata['background']);
        unset($slidedata['published']);
        unset($slidedata['first']);
        unset($slidedata['generator']);
        unset($slidedata['publishdates']);
        
        $slide['params'] = json_encode($slidedata);
        
        $this->_slidePointer++;
        return $slide;
    }

    function makeSlide($slide) {
        $this->_slidePointer = $slide['generator'] - 1;
        foreach ($slide AS $k => $v) {
            $slide[$k] = $this->replaceText(
                preg_replace_callback('/(data\-itemvalues=")([^"]*)/S', array($this, 'onAttributeData'), $v)
            );
        }
        return $slide;
    }

    function replaceText($text, $parseItems = true) {
        if($parseItems) $text = preg_replace_callback("/\[([a-zA-Z]+) values=\"(.*?)\"]/", array($this, 'onParseItem'), $text);
        return preg_replace_callback('/(\{nextend\|\|([a-zA-Z0-9,\|\|]+)\()?(\{\|(.*?)\-([0-9]+)\|\})(\)\})?/msS', array($this, 'onFunctionData'), $text);
    }

    function onFunctionData($matches) {
        return $this->onFunction($matches, $this->onData($matches));
    }

    function onData($matches) {
        return $this->getData(intval($matches[5]) - 1, $matches[4], $matches[3]);
    }
    
    function onParseItem($matches){
        $itemdata = json_decode(base64_decode($matches[2]), true);
        return '['.$matches[1].' values="'.base64_encode(json_encode($this->replaceText($itemdata, false))).'"]';
    }

    function onAttributeData($matches) {
        return $matches[1] . $this->replaceTextEncode($matches[2]);
    }

    function replaceTextEncode($text) {
        return preg_replace_callback('/(\{nextend\|\|([a-zA-Z0-9,\|\|]+)\()?(\{\|(.*?)\-([0-9]+)\|\})(\)\})?/msS', array($this, 'onFunctionDataEscape'), $text);
    }

    function onFunctionDataEscape($matches) {
        return $this->escape($this->onFunction($matches, $this->onData($matches)));
    }

    function getData($i, $name, $default) {
        $index = $this->_slidePointer * $this->_generatorgroup + $i;
        if (isset($this->_datasource[$index][$name])) {
            $v = $this->_datasource[$index][$name];
            return $v;
        }
        return $default;
    }

    function escape($s) {
        return str_replace(array('"', "\r\n", "\n", "\r"), array('\\&quot;', "\\r\\n", "\\n", "\\r"), $s);
    }

    function onFunction($matches, $s) {
        $fns = explode('||', $matches[2]);
        for ($i = count($fns) - 1; $i >= 0; $i--) {
            $fn = explode(',', $fns[$i]);
            switch ($fn[0]) {
                case 'cleanhtml':
                    $s = strip_tags($s, '<p><a><b><br><br/><i>');
                    break;
                case 'removehtml':
                    $s = strip_tags($s);
                    break;
                case 'splitbychars':
                    $s = substr($s, $fn[1], $fn[2]);
                    break;
                case 'splitbywords':
                    $len = strlen($s);
                    $pos = $fn[2] > $len ? $len : strpos($s, ' ', $fn[2]);
                    if($pos === false) $pos = $len;
                    $s = substr($s, 0, $pos);
                    break;
                case 'findimage':
                    $index = isset($fn[1]) ? intval($fn[1]) - 1 : 0;
                    preg_match_all('/<img.*?src=[\'"](.*?)[\'"][^>]+>/i', $s, $r);
                    if (isset($r[1]) && isset($r[1][$index])) {
                        $s = $r[1][$index];
                    } else {
                        $s = '';
                    }
                    break;

            }
        }
        if ($i !== -1) {
            if ($this->_tidy) {
                $tidy = new Tidy();
                return $tidy->repairString($s, array('show-body-only' => true, 'input-encoding' => $this->_tidyInputEncoding, 'output-encoding' => $this->_tidyOutputEncoding));
            }
        }
        return $this->closetags($s);
    }

    function loadList() {
        if ($this->_list == null) {
            $this->_group = array();
            $this->_list = array();
            NextendPlugin::callPlugin('nextendslidergenerator', 'onNextendSliderGeneratorList', array(&$this->_group, &$this->_list));
        }
    }

    function closetags($html) {
        #put all opened tags into an array
        preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU", $html, $result);
        $openedtags = $result[1];
        #put all closed tags into an array
        preg_match_all("#</([a-z]+)>#iU", $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        # all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        # close tags
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= "</" . $openedtags[$i] . ">";
            } else {
                unset ($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }
}