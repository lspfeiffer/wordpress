<script type="text/javascript">
var GalleryAjax = "<?php echo $this -> url(); ?>/<?php echo $this -> plugin_name; ?>-ajax.php";

jQuery(document).ready(function() {
	if (jQuery.isFunction(jQuery.fn.colorbox)) { jQuery('.colorbox').colorbox({maxWidth:'100%', maxHeight:'100%'}); }
	if (jQuery.isFunction(jQuery.fn.tooltip)) { jQuery(".galleryhelp a").tooltip(); }
});
</script>