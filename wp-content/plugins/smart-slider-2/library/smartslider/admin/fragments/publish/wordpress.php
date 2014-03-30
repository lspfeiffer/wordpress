<div class="blue-container">
  <table class="pane-table smartslider-slider-publish">
    <tr class="smartslider-slider-publish-heading">
        <td>Shortcode</td>
        <td>Page or Post editor</td>
        <td>PHP code</td>
    </tr>
    <tr class="smartslider-slider-publish-note">
        <td>Copy and paste this shortcode into your posts:</td>
        <td>Insert it into an existing post with the icon:</td>
        <td>Paste the PHP code into your template file:</td>
    </tr>
    <tr>
        <td>
            <code class="code-box">[smartslider2 slider="<?php echo $this->sliderid; ?>"]</code>
        </td>
        <td style="line-height:0px;">
            <img src="<?php echo NextendUri::pathToUri(NEXTEND_SMART_SLIDER2_ASSETS . 'admin/images/wordpress-publish.png') ?>" />
        </td>
        <td>
            <code class="code-box">
            &lt;?php <br />
              echo do_shortcode('[smartslider2 slider="<?php echo $this->sliderid; ?>"]');<br />
            ?&gt;</code>
        </td>
    </tr>
  </table>
</div>