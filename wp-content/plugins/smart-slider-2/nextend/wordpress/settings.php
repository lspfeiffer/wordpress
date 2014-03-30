<?php

$nextend_config = $data = get_option('nextend_config');
if(is_array($nextend_config)){
    setNextend('cachepath', rtrim(getNextend('cachepath','cache/'), '/\\').'/');
    foreach($nextend_config AS $k => $v){
        setNextend($k, $v);
    }
}

add_action('admin_menu', 'nextend_settings_add_page');

function nextend_settings_add_page() {
	add_submenu_page('options-general.php', 'Nextend Settings', 'Nextend Settings', 'manage_options', 'nextend_settings_page', 'nextend_settings_page');
}

function nextend_settings_page() {
?>
<div>
<h2>Nextend Global Settings</h2>
<?php
  if(isset($_POST['nextend'])){
      update_option('nextend_config', $_POST['nextend']);
  }
?>
<div id="nextend_configuration" class="postbox" style="margin: 0 20px 0 0;">
<form method="post" id="nextend-settings" action="<?php echo admin_url("options-general.php?page=nextend_settings_page"); ?>">
<?php
    
    $configurationXmlFile = NEXTENDLIBRARY.'wordpress/configuration.xml';
    if (NextendFilesystem::fileexists($configurationXmlFile)) {
        nextendimport('nextend.css.css');
        nextendimport('nextend.javascript.javascript');

        $css = NextendCss::getInstance();
        $js = NextendJavascript::getInstance();
        $css->addCssLibraryFile('wordpress/removeslug.css');
        $css->addCssLibraryFile('common.css');
        $css->addCssLibraryFile('window.css');
        $css->addCssLibraryFile('configurator.css');
        $js->loadLibrary('dojo');

        $js->addLibraryJsLibraryFile('dojo', 'dojo/window.js');
        $js->addLibraryJsAssetsFile('dojo', 'window.js');

        nextendimport('nextend.form.form');

        $control_name = 'nextend';

        $form = new NextendForm();
        $data = get_option('nextend_config');
        
        global $nextend;
        foreach($nextend AS $k => $v){
            if(!isset($data[$k])){
                $data[$k] = $v;
            }
        }
        $form->loadArray($data);

        $form->loadXMLFile($configurationXmlFile);

        ?>
        <div id="nextend-configurator-wp">
            <div class="gk_hack nextend-topbar"><div class="gk_hack nextend-topbar-logo"></div>
                <?php
                $manual = 'http://www.nextendweb.com/wiki/';
                if ($manual != "") {
                    ?>
                    <a href="<?php echo $manual; ?>" target="_blank" class="gk_hack nextend-topbar-button nextend-topbar-manual">Manual</a>
                    <?php
                }


                $support = 'http://www.nextendweb.com/accordion-menu/#support';
                if ($support != "") {
                    ?>
                    <a href="<?php echo $support; ?>" target="_blank" class="gk_hack nextend-topbar-button nextend-topbar-support">Support</a>
                    <?php
                }
                ?>
                
                <?php
                  if(defined('NEXTENDACCORDIONMENULITE')){
                ?>
                    <a href="http://www.nextendweb.com/accordion-menu/" target="_blank" class="gk_hack nextend-topbar-button nextend-topbar-getpro">Get PRO</a>
                <?php
                  }
                ?>
                
                <div id="nextend-configurator-save" onclick="njQuery('#nextend-settings').submit();" class="nextend-window-save"><div class="NextendWindowSave">SAVE</div></div>
            </div>
            <?php
            
            $form->render($control_name);

            $js->addLibraryJsAssetsFile('dojo', 'form.js');
            $js->addLibraryJs('dojo', '
                new NextendForm({
                  container: "nextend-configurator-wp",
                  data: ' . json_encode($form->_data) . ',
                  xml: "' . NextendFilesystem::toLinux(NextendFilesystem::pathToRelativePath($configurationXmlFile)) . '",
                  control_name: "' . $control_name . '",
                  url: "' . site_url('/wp-admin/admin-ajax.php?action=nextend') . '",
                  loadedJSS: ' . json_encode($js->generateArrayJs()) . ',
                  loadedCSS: ' . json_encode($css->generateArrayCSS()) . '
                });
            ', true);
            ?>
        </div>
        <?php
    }
?>
</div>
</form>
</div>
<?php 
} 