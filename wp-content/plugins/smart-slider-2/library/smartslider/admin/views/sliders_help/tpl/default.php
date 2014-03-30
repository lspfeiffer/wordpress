<?php
if(isset($_GET['clearlog'])){
    if(nextendIsWordPress()){
        delete_option( 'nextend_error' );
        wp_safe_redirect( $this->route('controller=help') );
    }
    else header('Location: '. $this->route('controller=help'));
    exit;
}

$this->loadFragment('headerstart');
?>
<?php
$this->loadFragment('headerend');
?>
<?php
$this->loadFragment('firstcolstart');
?>
<?php
$this->loadFragment('firstcolend');
?>
<?php
$this->loadFragment('secondcolstart');
$css = NextendCss::getInstance();
$css->addCssLibraryFile('form.css');
?>
<form method="post" action="" id="smartslider-form">           
  <style>  
  span.platform {
      background: none repeat scroll 0 0 #845CA5;
      border-radius: 2px 2px 2px 2px;
      color: #FFFFFF;
      font-size: 10px;
      font-weight: 600;
      line-height: 14px;
      padding: 0 5px 1px;
      text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
      text-transform: uppercase;
      vertical-align: 1px;
  }
  span.wordpress {
      background: none repeat scroll 0 0 #2F7799;
      border-radius: 2px 2px 2px 2px;
      color: #FFFFFF;
      font-size: 10px;
      font-weight: 600;
      line-height: 14px;
      padding: 0 5px 1px;
      text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
      text-transform: uppercase;
      vertical-align: 1px;
  }
  .nextend-element .nextend-text span{     color: #6C7581;     font-size: 12px;     font-weight: normal;     line-height: 11px;     text-shadow: 0 1px 1px white;   }     .nextend-element .nextend-text span a{     color: #6C7581;     font-size: 12px;     font-weight: normal;     line-height: 11px;     text-shadow: 0 1px 1px white;     text-decoration: none;   }   .nextend-element .nextend-text span a:HOVER{     color: #2485E3;   }   .ni .doc{     padding: 10px 20px 20px;     background-color: #EEF3F8;     border-bottom: 1px solid rgba(0, 0, 0, 0.1);   }   .ni .tutorial-videos{     padding: 20px;     background-color: #EEF3F8;     border-bottom: 1px solid rgba(0, 0, 0, 0.1);   }   .ni .tutorial-videos iframe{   }     .ni .tutorial-videos .video{     float: left;     margin: 0 50px 20px 0;   }   .ni .tutorial-videos span{       color: #6C7581;     font-size: 16px;     font-weight: normal;     line-height: 11px;     text-shadow: 0 1px 1px white;     text-align: center;     display: block;     margin-bottom: 10px;     font-family: 'Open Sans',Arial,sans-serif;   }     .ni .doc .categorycontainer .left,   .ni .doc .categorycontainer .right{     width: 48%;     float: left;     margin-left: 15px;     }    .ni .doc .categorycontainer{       color: #6C7581;     font-size: 12px;     font-weight: normal;     text-shadow: 0 1px 1px white;     font-family: 'Open Sans',Arial,sans-serif;   }   .ni .doc .categorycontainer dt{     font-size: 16px;     margin: 15px 0 5px;     font-weight: normal;   }     .ni .doc .categorycontainer dd,     .ni .doc .categorycontainer dl{     margin: 0;   }     .ni .doc .categorycontainer ul{     padding-left: 10px;   }   .ni .doc .categorycontainer li{     line-height: 20px;   }   .ni .doc .categorycontainer li a{   font-weight: 600;      color: #738AA2;     font-size: 13px;     text-decoration: none;     text-shadow: 0 1px 1px white;   }   .ni .doc .categorycontainer li a:HOVER,     .ni .doc .categorycontainer li:HOVER{     color: #2485E3;   }      
  </style>     
  <div class="nextend-form">   
    <div class="ni">
		<h2><?php echo NextendText::_('General_information'); ?></h2>             
      <table class="ni">                                 
          <tr>                         
            <td >                             
              <label for="slidertitle" id="slidertitle-lbl"><?php echo NextendText::_('Version_Number'); ?>
              </label>                           </td>                         
            <td >      
				<?php
                if(nextendIsJoomla()){
                    preg_match('/<version>(.*?)<\\/version>/', file_get_contents(JPATH_ADMINISTRATOR.'/components/com_smartslider2/smartslider2.xml'),$out);
                    echo $out[1];
                }else if(nextendIsWordpress()){
                    $plg = get_plugin_data( NEXTEND_SMART_SLIDER2.basename(NEXTEND_SMART_SLIDER2).'.php');
                    echo $plg['Version'];
                }else if(nextendIsMagento()){
                    echo (string) Mage::getConfig()->getNode()->modules->Nextend_SmartSlider2->version;
                }
                
                ?>                
			  </td>                     
          </tr>                                       
          <tr>                         
            <td>                             
              <label for="slidertitle" id="slidertitle-lbl"><?php echo NextendText::_('Documentation'); ?>
              </label>                           </td>                         
            <td >                      
                  <a href="http://www.nextendweb.com/wiki/smart-slider-documentation/"><?php echo NextendText::_('Read_the_documentation'); ?></a>                
        </td>                     
          </tr>                                       
          <tr>                         
            <td>                             
              <label for="slidertitle" id="slidertitle-lbl"><?php echo NextendText::_('Support'); ?> 
              </label>                           </td>                         
            <td>                       
				<a href="http://www.nextendweb.com/smart-slider#support"><?php echo NextendText::_('Write_a_support_ticket'); ?></a>                
			  </td>                     
          </tr>                          
        </tbody>             
      </table>               
	  <h2><?php echo NextendText::_('Possible problems'); ?></h2>             
    <div class="doc">
    <?php
    if(nextendIsWordPress()){
        $nextenderror = get_option( 'nextend_error' );
        if ( $nextenderror !== false && is_array($nextenderror)){
            if(isset($nextenderror['missinghead'])){
                foreach($nextenderror['missinghead'] AS $url){
                    NextendMessage::error(NextendText::_('get_header() or wp_head() call is missing from the template!'), 'Related page: '.$url.'<br /><br />Make sure that get_header() exists in the current template of the page or post at the beginning<br />Make sure that wp_head() exists before the closing &lt;/head&gt; tag in the [theme folder]/header.php<br /><a href="http://codex.wordpress.org/Function_Reference/wp_head" target="_blank">http://codex.wordpress.org/Function_Reference/wp_head</a>');
                }
            }
            if(isset($nextenderror['missingfooter'])){
                foreach($nextenderror['missingfooter'] AS $url){
                    NextendMessage::error(NextendText::_('get_footer() or wp_footer() call is missing from the template!'), 'Related page: '.$url.'<br /><br />Make sure that get_footer() exists in the current template of the page or post at the end of the file<br />Make sure that wp_footer() exists before the closing &lt;/body&gt; tag in the [theme folder]/footer.php<br /><a href="http://codex.wordpress.org/Function_Reference/wp_footer" target="_blank">http://codex.wordpress.org/Function_Reference/wp_footer</a>');
                }
            }
        }
    }
    ob_start();
    NextendMessage::show();
    $messages = ob_get_clean();
    if($messages){
        echo $messages;
    }else{
        NextendMessage::success('Everything seems fine!');
        NextendMessage::show();
    }
    ?>
        <div style="margin: 0 15px;">
            <a class="button b" href="<?php echo $this->route('controller=help&clearlog=1'); ?>">Clear error log</a>
            <?php if(nextendIsWordPress()){ ?>
                <br /><br /><br />
                <p>You can disable this log on the Nextend configuration with "<b>Log possible problems</b>" parameter: <a class="button small" href="<?php echo admin_url('options-general.php?page=nextend_settings_page'); ?>">Nextend configuration</a></p>
            <?php } ?>
        </div>
    </div>
    <h2><?php echo NextendText::_('Tutorial_videos'); ?></h2>               
    <div class="tutorial-videos nextend-clearfix">
        <iframe width="640" height="360" src="//www.youtube.com/embed/videoseries?list=PLSawiBnEUNfvCEnV5dGOAQABZ8TBx8fJg&vq=hd1080&hd=1" frameborder="0" allowfullscreen></iframe>                 
    </div>
</div>   
</form>

<?php
$this->loadFragment('secondcolend');
?>
<?php
$this->loadFragment('footer');
