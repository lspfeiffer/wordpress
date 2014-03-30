<div class="blue-container">
  <table class="pane-table smartslider-slider-publish">
    <tr class="smartslider-slider-publish-heading">
        <td>Widget code</td>
        <td>Layout XML</td>
    </tr>
    <tr class="smartslider-slider-publish-note">
        <td>Copy and paste this widget code into your pages:</td>
        <td>Paste the XML code into your layout xml files:</td>
    </tr>
    <tr>
        <td>
            <code class="code-box">{{widget type="nextendsmartslider2/slider" slider="<?php echo $this->sliderid; ?>"}}</code>
        </td>
        <td>
            <code class="code-box">
            &lt;block type="nextendsmartslider2/slider" name="smartslider<?php echo $this->sliderid; ?>"&gt;<br />
            &nbsp;&nbsp;&lt;action method="setData"&gt;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;slider&lt;/name&gt;<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;value&gt;<?php echo $this->sliderid; ?>&lt;/value&gt;<br />
            &nbsp;&nbsp;&lt;/action&gt;<br />
            &lt;/block&gt;<br />
            </code><br /><br />
        </td>
    </tr>
  </table>
</div>