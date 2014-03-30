
<div class="blue-container">
<?php
$accessModuleCreate = $this->canDo('core.create', 'com_modules');
if($accessModuleCreate):
?>
  <table class="pane-table smartslider-slider-publish">
    <tr class="smartslider-slider-publish-heading">
        <td>Module</td>
        <td>Load position</td>
        <td><a href="http://extensions.joomla.org/extensions/core-enhancements/coding-a-scripts-integration/embed-a-include/6402" target="_blank">Modules Anywhere</a></td>
    </tr>
    <tr class="smartslider-slider-publish-note">
        <td>This simple button allows you to create module from this slider:</td>
        <td>Create a default module from the slider:</td>
        <td>Create a default module from the slider:</td>
    </tr>
    <tr>
        <td>
            <a class="button big g" href="<?php echo $this->route('controller=sliders&view=sliders_generator&action=createmodule&sliderid=' . $this->sliderid); ?>">Create module</a>
            <br /><br /><br />
        </td>
        <td>
            <a class="button g" target="_blank" href="<?php echo $this->route('controller=sliders&view=sliders_generator&action=createmodule&sliderid=' . $this->sliderid); ?>">Create module</a>
            <br /><br />
            <p>and then use the shortcode:</p>
            <br />
            <code class="code-box">{loadposition smartslider-<?php echo $this->sliderid; ?>}</code>
            <br /><br />
        </td>
        <td>
            <a class="button g" target="_blank" href="<?php echo $this->route('controller=sliders&view=sliders_generator&action=createmodule&sliderid=' . $this->sliderid); ?>">Create module</a>
            <br /><br />
            <p>and then use the shortcode:</p>
            <br />
            <code class="code-box">{modulepos smartslider-<?php echo $this->sliderid; ?>}</code>
            <br /><br />
        </td>
    </tr>
  </table>
<?php
else:
?>
<div style="width: 50%" class="box y"><h3>Limited access</h3><p>You do NOT have access to the create module resource!</p></div>
<?php
endif;
?>
</div>