
(function($) {
    window.smartSliderSlideOrdering = {
        init: function(url) {
            var ul = $(".smartslider-slides-list");
            ul.qtip({
                id: "slides-ordering",
                content: {
                    text: window.ss2lang.Ordering_saved
                },
                show: {
                    event: false,
                    ready: false
                },
                hide: false,
                position: {
                    my: "bottom center",
                    at: "top center",
                    target: ul,
                    viewport: $(window)
                }
            });
            ul.sortable({
                items: "li.smartslider-slide",
                placeholder: "smartslider-placeholder",
                forcePlaceholderSize: true,
                axis: "y",
                handle: ".smartslider-icon-ordering",
                stop: function(event, ui) {
                    var ajaxcall = url;
                    ajaxcall += (ajaxcall.indexOf("?") ? "&" : "?") + $(this).sortable("serialize");
                    $.ajax({
                        url: ajaxcall,
                        context: document.body
                    }).done(function() {
                        ul.qtip("show");
                        setTimeout(function() {
                            ul.qtip("hide");
                        }, 3000);
                    });
                }
            });
            ul.disableSelection();
        }
    }
})(njQuery);