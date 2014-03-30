/**
 * Slideshow Gallery TinyMCE Plugin
 * @author Tribulant Software
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack("gallery");

	tinymce.create('tinymce.plugins.gallery', {
		init: function(ed, url) {
			ed.addCommand('mcegallery', function() {			
				ed.windowManager.open({
					file : url + '/dialog.php',
					width : 500,
					height : 300,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('gallery', {
				title : 'gallery.desc',
				cmd : 'mcegallery',
				//image : url + '/gallery.png'
			});			
		},		
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : 'Slideshow Gallery TinyMCE Plugin',
				author : 'Tribulant Software',
				authorurl : 'http://tribulant.com',
				infourl : 'http://tribulant.com',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('gallery', tinymce.plugins.gallery);
})();