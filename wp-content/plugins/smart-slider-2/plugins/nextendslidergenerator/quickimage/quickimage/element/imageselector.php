<?php

class NextendElementImageSelector extends NextendElementHidden {
    
    var $_mode = 'hidden';
    
    function fetchElement() {
        $css = NextendCss::getInstance();
        $css->addCssFile(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/css/createquick.css');
        $css->addCssLibraryFile('element/textarea.css');       
        
        $js = NextendJavascript::getInstance();
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.core.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.widget.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.mouse.min.js');
        $js->addLibraryJsLibraryFile('jquery', 'ui/jquery.ui.sortable.min.js');
        $this->_value = $this->_form->get($this->_name, $this->_default);
        $images = json_decode($this->_value, true);
        
        $html = '<div id="selected-images-container" class="">';
        if(nextendIsWordpress()){
              add_filter('media_view_strings', 'custom_media_uploader_tabs', 5);
              function custom_media_uploader_tabs( $strings ) {
                  $strings['insertMediaTitle'] = "Image";
                  $strings['insertIntoPost'] = "Add to slider";
                  // remove options
                  if (isset($strings['createGalleryTitle'])) unset($strings['createGalleryTitle']);
                  if (isset($strings['insertFromUrlTitle'])) unset($strings['insertFromUrlTitle']);
                  return $strings;
              }
              wp_enqueue_style('editor');
              if(function_exists( 'wp_enqueue_media' )){
                  wp_enqueue_media();
              }else{
                  wp_enqueue_style('thickbox');
                  wp_enqueue_script('media-upload');
                  wp_enqueue_script('thickbox');
              }
          
          $html.= '<a title="Add Image" data-editor="content" class="button add-image" href="#">
              <span class="wp-media-buttons-icon"></span> Add Image
          </a>
          <div style="clear: both;"></div>';
        }else if(nextendIsMagento()){
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'extjs/ext-tree.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'extjs/ext-tree-checkbox.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'extjs/fix-defer.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'mage/adminhtml/variables.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'mage/adminhtml/wysiwyg/widget.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'lib/flex.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'lib/FABridge.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'mage/adminhtml/flexuploader.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'mage/adminhtml/browser.js"></script>';
            $html.= '<script type="text/javascript" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'prototype/window.js"></script>';
            
            $html.= '<link rel="stylesheet" type="text/css" href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS).'prototype/windows/themes/default.css" media="screen"/>';
            $html.= '<link rel="stylesheet" type="text/css" href="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'adminhtml/default/default/lib/prototype/windows/themes/magento.css" media="screen"/>';
          
            $html.= '<a title="Add Image" data-editor="content" class="button add-image" href="#">
                <span class="wp-media-buttons-icon"></span> Add Image
            </a>
            <input type="hidden" id="magentohidden" />
            <div style="clear: both;"></div>';
        }
    
        $html.= '<div id="delete-image"></div>
                <ul id="selected-images">';
        if(is_array($images)){
            $i = 0;
            foreach($images AS $img){
                $html.='<li>';
                $html.='<img src="'.$img['image'].'" /><div class="field-container">';
                $html.='
<div class="nextend-mixed-group">
  <div class="nextend-mixed-label">
    <label for="title'.$i.'" id="title'.$i.'-lbl">Title</label>
  </div>
  <div class="nextend-mixed-element">
    <div class="nextend-text ">
      <input type="text" autocomplete="off" style="width:390px;" value="'.$img['title'].'" name="" id="title'.$i.'">
    </div>
  </div>
</div>';
                $html.='
<div class="nextend-mixed-group" style="clear: both;">
  <div class="nextend-mixed-label">
    <label for="link'.$i.'" id="link'.$i.'-lbl">Link</label>
  </div>
  <div class="nextend-mixed-element">
    <div class="nextend-text">
      <input type="text" autocomplete="off" style="width:390px;" value="'.$img['url'].'" name="" id="link'.$i.'">
    </div>
  </div>
</div>';
                $html.='
<div class="nextend-mixed-group" style="clear: both;">
  <div class="nextend-mixed-label">
    <label for="description'.$i.'" id="description'.$i.'-lbl">Description</label>
  </div>
  <div class="nextend-mixed-element">
    <div class="nextend-textarea ">
      <textarea autocomplete="off" style="width:400px; height: 40px;" name="" id="description'.$i.'">'.$img['description'].'</textarea>
    </div>
  </div>
</div>';
                $html.='</div><div style="clear:both;"></div></li>';
                $i++;
            }
        }
        $html.= '</ul>
                <div style="clear: both;"></div>
            </div>'.parent::fetchElement();
            
       if(nextendIsJoomla()){
        		$app = JFactory::getApplication();
        		$user = JFactory::getUser();
        		$extension = $app->input->get('option');
        		
            $link = 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;e_name=&amp;asset=' . $extension . '&amp;author=' . $user->id;
        
            $html.= '<div style="margin: 0 -10px -5px;"><h2>Choose images</h2>
            <div class="blue-container">
                <iframe frameborder="0" src="'.$link.'" id="image-chooser" width="100%" style="float: left; width: 100%;" height="0"></iframe>
            </div></div>';
        }
        ob_start();
        ?>
<script type="text/html" id="image-form-skeleton">
    <div class="field-container">
    <div class="nextend-mixed-group">
      <div class="nextend-mixed-label">
        <label for="title'.$i.'" id="title'.$i.'-lbl">Title</label>
      </div>
      <div class="nextend-mixed-element">
        <div class="nextend-text ">
          <input type="text" autocomplete="off" style="width:390px;" value="" name="" id="title'.$i.'">
        </div>
      </div>
    </div>
    
    <div class="nextend-mixed-group" style="clear: both;">
      <div class="nextend-mixed-label">
        <label for="link'.$i.'" id="link'.$i.'-lbl">Link</label>
      </div>
      <div class="nextend-mixed-element">
        <div class="nextend-text">
          <input type="text" autocomplete="off" style="width:390px;" value="" name="" id="link'.$i.'">
        </div>
      </div>
    </div>
    
    <div class="nextend-mixed-group" style="clear: both;">
      <div class="nextend-mixed-label">
        <label for="description'.$i.'" id="description'.$i.'-lbl">Description</label>
      </div>
      <div class="nextend-mixed-element">
        <div class="nextend-textarea ">
          <textarea autocomplete="off" style="width:400px; height: 40px;" name="" id="description'.$i.'"></textarea>
        </div>
      </div>
    </div>
    </div>
    <div style="clear:both;"></div>
</script>
<script>
njQuery(window).ready(function(){
    var $ = njQuery,
        images = $('#selected-images'),
        deleteimage = $('#delete-image'),
        form = $('#smartslider-form'),
        skeleton = njQuery('#image-form-skeleton').html(),
        iii = 10000;
    
    function addImageToList(img){
        var el = $('<li><img src="'+img+'" />'+skeleton.replace(/'\.\$i\.'/g, iii)+'</li>').appendTo(images);
        iii++;
        images.sortable( "refresh" );
        $(window).trigger('resize');
        return el;
    }
    
    <?php if(nextendIsJoomla()): ?>
    var folder = "<?php echo NextendUri::pathToUri(JPATH_ROOT . '/' . JComponentHelper::getParams('com_media')->get('image_path', 'images'). '/')?>",
        iframe = $('#image-chooser'),
        frame = $('#image-chooser');
    
    frame.on('load', function(){
        frame = $(this.contentWindow.document);
        frame.find('#imageframe').next('.well').remove();
        frame.find('#imageframe').next('fieldset').remove();
        frame.find('#imageframe').prev('fieldset').find('.fltrt').remove();
        frame.find('#imageForm').find('.pull-right').remove();
        frame.find('#upbutton').css('verticalAlign', 'top');
        this.contentWindow.ImageManager.populateFields = function(file){
            addImageToList(folder+file);
        }
        frame.find('#system-message-container').css('paddingTop', '15px');
        frame.find('body').css('overflow', 'hidden').css('padding', '0 15px');
        iframe.css('height', frame.find('html').prop('scrollHeight'));
        $(window).trigger('resize');
    });
    <?php elseif(nextendIsWordpress()): ?>
    		var file_frame;
    		
    		jQuery('#smartslider-admin .add-image').on('click', function(event){
    			event.preventDefault();
    
    			// If the media frame already exists, reopen it.
    			if ( file_frame ) {
    				file_frame.open();
    				return;
    			}
    
    			// Create the media frame.
    			file_frame = wp.media.frames.file_frame = wp.media({
    				multiple: 'add',
    				frame: 'post',
    				library: {type: 'image'}
    			});
    
    			// When an image is selected, run a callback.
    			file_frame.on('insert', function(selection) {
              var state = file_frame.state();
              
              selection = selection || state.get('selection');
              
              if ( ! selection )
					       return; 
                 
              selection.map( function( attachment ) {
    					   var display = state.display( attachment ).toJSON();
                 
        					attachment = attachment.toJSON();
                  
                  var el = addImageToList(attachment.url);
                  el.find('input[id^=title]').val(attachment.title);
                  if(attachment.caption != '') el.find('textarea[id^=description]').val(attachment.caption);
                  else if(attachment.description != '') el.find('textarea[id^=description]').val(attachment.description);
                  
                  if(typeof display !== 'undefined' && typeof display.linkUrl !== 'undefined') el.find('input[id^=link]').val(display.linkUrl);
      				});
    			});
    
    			file_frame.open();
    
    			// Remove the Media Library tab (media_upload_tabs filter is broken in 3.6)
    			jQuery(".media-menu a:contains('Media Library')").remove();
    		});
    <?php elseif(nextendIsMagento()): ?>
    
    var magentohidden = document.getElementById('magentohidden');
    
Mediabrowser.prototype.insert = Mediabrowser.prototype.insert.wrap(function(parentMethod, event) {
    var div;
    if (event != undefined) {
        div = $(Event.findElement(event, 'DIV'));
    } else {
        $$('div.selected').each(function (e) {
            div = $('#'+e.id);
        });
    }
    if (div.length == 0) {
        return false;
    }
    var targetEl = this.getTargetElement();
    if (! targetEl) {
        alert("Target element not found for content update");
        Windows.close('browser_window');
        return;
    }
    div = div[0];
    var params = {filename:div.id, node:this.currentNode.id, store:this.storeId};

    if (targetEl.tagName.toLowerCase() == 'textarea') {
        params.as_is = 1;
    }

    new Ajax.Request(this.onInsertUrl, {
        parameters: params,
        onSuccess: function(transport) {
            try {
                this.onAjaxSuccess(transport);
                if (this.getMediaBrowserOpener()) {
                    self.blur();
                }
                Windows.close('browser_window');
                if (targetEl.tagName.toLowerCase() == 'input') {
                    targetEl.value = transport.responseText;

                    // AntoineK overload START
                    if (varienGlobalEvents) {
                        varienGlobalEvents.fireEvent('mediachooserChange' + targetEl.id, transport.responseText);
                    }
                    // AntoineK overload END

                } else {
                    updateElementAtCursor(targetEl, transport.responseText);
                    if (varienGlobalEvents) {
                        varienGlobalEvents.fireEvent('tinymceChange');
                    }
                }
            } catch (e) {
                alert(e.message);
            }
        }.bind(this)
    });
});
        jQuery('#smartslider-admin .add-image').on('click', function(event){
            event.preventDefault();
            MediabrowserUtility.openDialog('<?php echo Mage::helper("adminhtml")->getUrl('adminhtml/cms_wysiwyg_images_chooser/index', array('target_element_id' => 'magentohidden', 'store' => 'undefined', 'type' => 'image')); ?>');
        });

        varienGlobalEvents.attachEventHandler('mediachooserChangemagentohidden', function(url){
            addImageToList(url);
        });
    <?php endif; ?>
    
    $(document.body).css('overflow', 'auto');
    images.sortable({
        connectWith: deleteimage,
        placeholder: "ui-state-highlight",
        forcePlaceholderSize: true,
        appendTo: document.body,
        tolerance: 'pointer',
        handle: "img",
        over: function(){
            $(window).trigger('resize');
        },
        out: function(){
            $(window).trigger('resize');
        }
    });
    
    $(window).on('resize', function(){
        deleteimage.height(images.height());
    });
    
    deleteimage.sortable({
        tolerance: 'pointer',
        appendTo: document.body,
        over: function(){
            deleteimage.addClass('over');
            $(window).trigger('resize');
        },
        out: function(){
            deleteimage.removeClass('over');
            $(window).trigger('resize');
        },
        update: function(event, ui) {
            ui.item.remove();
            $(window).trigger('resize');
        } 
    });
    images.find('img').disableSelection();
    
    form.on('submit', function(){
        var data = {},
            i = 0;
        images.find('img').each(function(){
            data[i] = {};
            data[i].image = this.src;
            var cont = $(this).parent();
            data[i].title = cont.find('input[id^=title]').val();
            data[i].url = cont.find('input[id^=link]').val();
            data[i].description = cont.find('textarea[id^=description]').val();
            i++;
        });
        $('#<?php echo $this->_id; ?>').val(JSON.stringify(data));
    });
});
</script>
        <?php
        $html.= ob_get_clean();
        return $html;
    }
}
