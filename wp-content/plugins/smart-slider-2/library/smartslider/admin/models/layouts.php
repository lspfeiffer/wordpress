<?php

nextendimportsmartslider2('nextend.smartslider.admin.models.base');

class NextendSmartsliderAdminModelLayouts extends NextendSmartsliderAdminModelBase {

    function getLayout($id) {
        $db = NextendDatabase::getInstance();
        $db->setQuery('SELECT * FROM #__nextend_smartslider_layouts WHERE id = ' . $db->quote($id));
        return $db->loadAssoc();
    }

    function getLayouts() {
        $db = NextendDatabase::getInstance();
        $db->setQuery('SELECT * FROM #__nextend_smartslider_layouts ORDER BY title');
        return $db->loadAssocList();
    }

    function getCoreLayouts() {

        return array(
            array(
                'class' => 'one-column',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 10%; left: 10%; width: 80%; height: 20%; position: absolute; z-index: 7; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="heading" data-itemvalues="{&quot;priority&quot;:&quot;1&quot;,&quot;heading&quot;:&quot;Heading&quot;,&quot;link&quot;:&quot;#|*|_self|*|default&quot;,&quot;fontclass&quot;:&quot;sliderfont2&quot;,&quot;skins&quot;:&quot;&quot;,&quot;css&quot;:&quot;padding: 0;\n                    margin: 0;\n                    background: none;\n                    box-shadow: none;&quot;,&quot;class&quot;:&quot;&quot;}">
          <h1 style="padding: 0;
                    margin: 0;
                    background: none;
                    box-shadow: none;" class="sliderfont2 ">
                  '.NextendText::_('Heading').'
                </h1>
        </div></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2" class="smart-slider-layer" style="top: 30%; left: 10%; width: 80%; height: 60%; position: absolute; z-index: 8; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="paragraph" data-itemvalues="{&quot;content&quot;:&quot;Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.&quot;,&quot;fontclass&quot;:&quot;sliderfont6&quot;,&quot;css&quot;:&quot;&quot;,&quot;class&quot;:&quot;&quot;}"><p class="sliderfont6 " style="">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.</p></div></div>'
            ),
            array(
                'class' => 'two-column',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 10%; left: 10%; width: 80%; height: 20%; position: absolute; z-index: 2; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="heading" data-itemvalues="{&quot;priority&quot;:&quot;1&quot;,&quot;heading&quot;:&quot;Heading&quot;,&quot;link&quot;:&quot;#|*|_self|*|default&quot;,&quot;fontclass&quot;:&quot;sliderfont2&quot;,&quot;skins&quot;:&quot;&quot;,&quot;css&quot;:&quot;padding: 0;\n                    margin: 0;\n                    background: none;\n                    box-shadow: none;&quot;,&quot;class&quot;:&quot;&quot;}">
          <h1 style="padding: 0;
                    margin: 0;
                    background: none;
                    box-shadow: none;" class="sliderfont2 ">
                  '.NextendText::_('Heading').'
                </h1>
        </div></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2" class="smart-slider-layer" style="top: 30%; left: 10%; width: 38%; height: 59.967%; position: absolute; z-index: 3; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="paragraph" data-itemvalues="{&quot;content&quot;:&quot;Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.&quot;,&quot;fontclass&quot;:&quot;sliderfont6&quot;,&quot;css&quot;:&quot;&quot;,&quot;class&quot;:&quot;&quot;}"><p class="sliderfont6 " style="">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.</p></div></div>                    <div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2 - copy" class="smart-slider-layer" style="top: 30%; left: 52%; width: 38%; height: 59.967%; position: absolute; z-index: 4; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="paragraph" data-itemvalues="{&quot;content&quot;:&quot;Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.&quot;,&quot;fontclass&quot;:&quot;sliderfont6&quot;,&quot;css&quot;:&quot;&quot;,&quot;class&quot;:&quot;&quot;}"><p class="sliderfont6 " style="">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.</p></div></div>'
            ),
            array(
                'class' => 'text-image',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 10%; left: 10%; width: 38%; height: 20%; position: absolute; z-index: 2; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="heading" data-itemvalues="{&quot;priority&quot;:&quot;1&quot;,&quot;heading&quot;:&quot;Heading&quot;,&quot;link&quot;:&quot;#|*|_self|*|default&quot;,&quot;fontclass&quot;:&quot;sliderfont2&quot;,&quot;skins&quot;:&quot;&quot;,&quot;css&quot;:&quot;padding: 0;\n                    margin: 0;\n                    background: none;\n                    box-shadow: none;&quot;,&quot;class&quot;:&quot;&quot;}">
          <h1 style="padding: 0;
                    margin: 0;
                    background: none;
                    box-shadow: none;" class="sliderfont2 ">
                  '.NextendText::_('Heading').'
                </h1>
        </div></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2" class="smart-slider-layer" style="top: 30%; left: 10%; width: 38%; height: 59.967%; position: absolute; z-index: 3; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="paragraph" data-itemvalues="{&quot;content&quot;:&quot;Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.&quot;,&quot;fontclass&quot;:&quot;sliderfont6&quot;,&quot;css&quot;:&quot;&quot;,&quot;class&quot;:&quot;&quot;}"><p class="sliderfont6 " style="">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.</p></div></div>                    <div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2 - copy" class="smart-slider-layer" style="top: 10%; left: 52%; width: 38%; height: 80%; position: absolute; z-index: 4; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="image" data-itemvalues="{&quot;image&quot;:&quot;http://www.nextendweb.com/static/placeholder.png&quot;,&quot;link&quot;:&quot;#|*|_self|*|default&quot;,&quot;size&quot;:&quot;auto|*|&quot;,&quot;alt&quot;:&quot;-&quot;,&quot;css&quot;:&quot;&quot;}"><a href="" onclick="if(this.getAttribute(\'href\') === \'\') return false;" target="_self" style="background: none !important;cursor:default;"> <img src="http://www.nextendweb.com/static/placeholder.png" style="max-width: 100%; ;width:auto;height:;" alt="-"> </a></div></div>'
            ),
            array(
                'class' => 'text-youtube',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 10%; left: 10%; width: 38%; height: 20%; position: absolute; z-index: 2; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="heading" data-itemvalues="{&quot;priority&quot;:&quot;1&quot;,&quot;heading&quot;:&quot;Heading&quot;,&quot;link&quot;:&quot;#|*|_self|*|default&quot;,&quot;fontclass&quot;:&quot;sliderfont2&quot;,&quot;skins&quot;:&quot;&quot;,&quot;css&quot;:&quot;padding: 0;\n                    margin: 0;\n                    background: none;\n                    box-shadow: none;&quot;,&quot;class&quot;:&quot;&quot;}">
          
            <h1 style="padding: 0;
                    margin: 0;
                    background: none;
                    box-shadow: none;" class="sliderfont2 ">
                  '.NextendText::_('Heading').'
                </h1>
        </div></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2" class="smart-slider-layer" style="top: 30%; left: 10%; width: 38%; height: 59.967%; position: absolute; z-index: 3; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="paragraph" data-itemvalues="{&quot;content&quot;:&quot;Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.&quot;,&quot;fontclass&quot;:&quot;sliderfont6&quot;,&quot;css&quot;:&quot;&quot;,&quot;class&quot;:&quot;&quot;}"><p class="sliderfont6 " style="">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.</p></div></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2 - copy" class="smart-slider-layer" style="top: 10%; left: 52%; width: 38%; height: 80%; position: absolute; z-index: 4; display: block;" data-animation="slide"><div style="" class="smart-slider-items" data-item="youtube" data-itemvalues="{&quot;youtubeurl&quot;:&quot;http://www.youtube.com/watch?v=cGET38O6IJ4&quot;,&quot;defaultimage&quot;:&quot;maxresdefault&quot;,&quot;autoplay&quot;:&quot;1&quot;,&quot;theme&quot;:&quot;dark&quot;}">
        <img src="//img.youtube.com/vi/cGET38O6IJ4/maxresdefault.jpg" style="width: 100%; height: 100%;">
        <div id="nextend-element-d934cGET38O6IJ4" data-youtubecode="cGET38O6IJ4" data-autoplay="1" data-theme="dark" style="position: absolute; top:0; left: 0; display: none; width: 100%; height: 100%;"><!--smartslideryoutubeitem,nextend-element-d934,cGET38O6IJ4--></div>
        </div></div>'
            ),
            array(
                'class' => 'full-image',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 0%; left: 0%; width: 100%; height: 100%; position: absolute; z-index: 1;" data-animation="slide"><div style="" class="smart-slider-items" data-item="image" data-itemvalues="{&quot;image&quot;:&quot;http://www.nextendweb.com/static/placeholder.png&quot;,&quot;link&quot;:&quot;#|*|_self|*|default&quot;,&quot;size&quot;:&quot;100%|*|auto&quot;,&quot;alt&quot;:&quot;-&quot;,&quot;css&quot;:&quot;&quot;}"><a href="" onclick="if(this.getAttribute(\'href\') === \'\') return false;" target="_self" style="background: none !important;cursor:default;"> <img src="http://www.nextendweb.com/static/placeholder.png" style="max-width: 100%; ;width:100%;height:auto;" alt="-"> </a></div></div>'
            ),
            array(
                'class' => 'full-youtube',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 0%; left: 0%; width: 100%; height: 100%; position: absolute; z-index: 1;" data-animation="slide"><div style="" class="smart-slider-items" data-item="youtube" data-itemvalues="{&quot;youtubeurl&quot;:&quot;http://www.youtube.com/watch?v=cGET38O6IJ4&quot;,&quot;defaultimage&quot;:&quot;maxresdefault&quot;,&quot;autoplay&quot;:&quot;1&quot;,&quot;theme&quot;:&quot;dark&quot;}">
        <img src="//img.youtube.com/vi/cGET38O6IJ4/maxresdefault.jpg" style="width: 100%; height: 100%;">
        <div id="nextend-element-cda8cGET38O6IJ4" data-youtubecode="cGET38O6IJ4" data-autoplay="1" data-theme="dark" style="position: absolute; top:0; left: 0; display: none; width: 100%; height: 100%;"><!--smartslideryoutubeitem,nextend-element-cda8,cGET38O6IJ4--></div>
        </div></div>'
            ),
            array(
                'class' => 'two-column-layer',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 5%; left: 5%; width: 43%; height: 90%; position: absolute; z-index: 2;" data-animation="slide"></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2" class="smart-slider-layer" style="top: 5%; left: 52%; width: 43%; height: 90%; position: absolute; z-index: 3;" data-animation="slide"></div>'
            ),
            array(
                'class' => 'two-column-bottom-layer',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 5%; left: 5%; width: 43%; height: 60%; position: absolute; z-index: 2;" data-animation="slide"></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2" class="smart-slider-layer" style="top: 5%; left: 52%; width: 43%; height: 60%; position: absolute; z-index: 3;" data-animation="slide"></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2 - copy" class="smart-slider-layer" style="top: 70%; left: 5%; width: 90%; height: 25%; position: absolute; z-index: 4;" data-animation="slide"></div>'
            ),
            array(
                'class' => 'two-left-right-layer',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 5%; left: 5%; width: 43%; height: 60%; position: absolute; z-index: 2;" data-animation="slide"></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2" class="smart-slider-layer" style="top: 5%; left: 52%; width: 43%; height: 90%; position: absolute; z-index: 3;" data-animation="slide"></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2 - copy" class="smart-slider-layer" style="top: 70%; left: 5%; width: 43%; height: 25%; position: absolute; z-index: 4;" data-animation="slide"></div>'
            ),
            array(
                'class' => 'two-rows-layers',
                'slide' => '<div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #1" class="smart-slider-layer" style="top: 5%; left: 5%; width: 90%; height: 43%; position: absolute; z-index: 2;" data-animation="slide"></div><div data-parallaxout="0.45" data-delayout="0" data-easingout="linear" data-durationout="500" data-animationout="0" data-playoutafter="0" data-parallaxin="0.45" data-delayin="0" data-easingin="linear" data-durationin="500" data-animationin="0" data-name="Layer #2 - copy" class="smart-slider-layer" style="top: 52%; left: 5%; width: 90%; height: 43%; position: absolute; z-index: 4;" data-animation="slide"></div>'
            )
        );
    }

    function renderAddForm($data = array()) {
        $this->editForm($data);
    }

    function renderEditForm($id = 0) {
        global $smartsliderlayout;
        if ($id == 0)
            $this->redirectToCreate();

        $layout = $this->getLayout($id);
        if ($layout === null)
            $this->redirectToCreate();

        $data = json_decode($layout['params'], true);
        if ($data == null)
            $data = array();
        $data['title'] = $layout['title'];
        $data['slide'] = $layout['slide'];
        $smartsliderlayout = $data['slide'];
        $this->editForm($data);
    }

    function editForm($data = array()) {
        $_GET['sliderid'] = -1;

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();

        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');

        $configurationXmlFile = dirname(__FILE__) . '/forms/layout.xml';
        $js->loadLibrary('dojo');

        nextendimport('nextend.form.form');
        $form = new NextendForm();
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        echo $form->render('layout');
    }

    function create($layout) {
        if (!isset($layout['title']))
            return false;
        if ($layout['title'] == '')
            $layout['title'] = 'New layout';

        $db = NextendDatabase::getInstance();
        
        $db->insert('#__nextend_smartslider_layouts', array(
            'title' => $layout['title'],
            'slide' => base64_decode($layout['slide']),
            'params' => ''
        ));
        
        return $db->insertid();
    }

    function save($id, $layout) {
        if (!isset($layout['title']) || $id <= 0)
            return false;
        if ($layout['title'] == '')
            $layout['title'] = 'New layout';
        $db = NextendDatabase::getInstance();
        
        $db->update('#__nextend_smartslider_layouts', array(
            'title' => $layout['title'],
            'slide' => base64_decode($layout['slide'])
        ), 'id = ' . $db->quote($id));

        return $id;
    }

    function delete($id) {
        $db = NextendDatabase::getInstance();
        $db->setQuery('DELETE FROM #__nextend_smartslider_layouts WHERE id=' . $db->quote($id));
        $db->query();
    }

}