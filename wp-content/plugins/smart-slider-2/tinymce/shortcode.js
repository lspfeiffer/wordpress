(function() {
   tinymce.create('tinymce.plugins.nextendsmartslider2', {
      init : function(ed, url) {
         ed.addButton('nextendsmartslider2', {
            title : 'Smart Slider 2',
            image : url+'/icon.png',
            onclick : function() {
                NextendSmartSliderModal(ed);
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "Nextend Smart Slider 2",
            author : 'Roland Soos',
            authorurl : 'http://www.nextendweb.com',
            infourl : 'http://www.nextendweb.com/smart-slider/',
            version : "1.0"
         };
      }
   });
   tinymce.PluginManager.add('nextendsmartslider2', tinymce.plugins.nextendsmartslider2);
})();