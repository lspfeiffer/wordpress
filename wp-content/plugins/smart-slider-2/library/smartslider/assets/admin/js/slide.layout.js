(function ($, scope, undefined) {

    scope.ssadminLayoutsClass = NClass.extend({
        active: false,
        init: function (layers, layouturl) {
            var $this = this;
            this.layers = layers;
            this.slide = layers.slide;

            this.layouturl = layouturl;

            this.views = this.layers.views;
            this.toolboxviews = this.layers.toolboxviews;

            this.views.eq(0).on('click', function () {
                $this.switchToLayoutTab();
            });

            this.defaultAndCustom();

            var $dl = $('.smartslider-slide-layout-custom > dl');

            $('.smartslider-savelayout').on('click', function (e) {
                e.preventDefault();
                if ($this.slide[0].ssanimation === 0) {
                    var base64HTML = Base64.encode($this.layers.getHTML()),
                        title = $('#slidetitle').val();
                    if (title == '') {
                        alert(window.ss2lang.Title_is_empty_Save_failed);
                        return;
                    }

                    $.ajax({
                        type: "POST",
                        url: layouturl,
                        data: {
                            save: 1,
                            ajax: 1,
                            layout: {
                                title: title,
                                slide: base64HTML
                            }
                        },
                        success: function (layoutdata) {
                            var dts = $dl.find('> dt');
                            var dt = $('<dt class="'+((dts.length + 1) % 2 ? 'odd' : 'even')+
                            ' smartslider-button-blue-active smartslider-icon-container">' +
                                '<a class="smartslider-button-link smartslider-load-layout" href="#">' + title + '</a>' +
                                '<div class="smartslider-layout-container"></div>' +
                            '</dt>');
                            dt.find('.smartslider-layout-container').html(layoutdata);
                            dt.appendTo($dl);
                            dt.find('.smartslider-load-layout').on('click', function (e) {
                                e.preventDefault();
                                var layout = $(this).siblings('.smartslider-layout-container');
                
                                $this.layers.deleteLayers();
                                layout.children().each(function (i, layer) {
                                    $this.layers.addLayer(layer);
                                });
                            });
                            $('.smartslider-layout-custom').trigger('click');
                        },
                        fail: function () {
                            alert(window.ss2lang.Unexpected_error_Saving_failed);
                        }
                    });

                    return true;
                }
            });

            this.initCustom();
        },
        switchToLayoutTab: function () {
            this.views.removeClass('active');
            this.views.eq(0).addClass('active');
            this.toolboxviews.removeClass('active');
            this.toolboxviews.eq(0).addClass('active');
            this.toolboxviews.parent().css((window.nextendDir == 'rtl' ? 'marginRight' : 'marginLeft'), (window.nextendDir == 'rtl' ? '-200%' : '0%'));
            $('#smartslider-admin').removeClass('smartslider-item-mode-active');
            $('#smartslider-admin').addClass('smartslider-layer-mode-active');
            $(window).trigger('resize');
        },
        defaultAndCustom: function () {
            var $toolbox = $('#smartslider-slide-toolbox'),
                $list = $('.smartslider-layout-default'),
                $edit = $('.smartslider-layout-custom'),
                classes = ['smartslider-slide-layout-default-active', 'smartslider-slide-layout-custom-active'];
            $edit.on('click', function () {
                $toolbox.addClass(classes[1]).removeClass(classes[0]);
            });
            $list.on('click', function () {
                $toolbox.addClass(classes[0]).removeClass(classes[1]);
            });
            this.switchToEdit = function () {
                $toolbox.addClass(classes[1]).removeClass(classes[0]);
            }
        },
        initCustom: function () {
            var $this = this;
            this.customs = $('.smartslider-slide-layout-pane-inner .smartslider-load-layout');
            this.customs.on('click', function (e) {
                e.preventDefault();
                var layout = $(this).siblings('.smartslider-layout-container');

                $this.layers.deleteLayers();
                layout.children().each(function (i, layer) {
                    $this.layers.addLayer(layer);
                });
                $this.layers.parent.$slider.data('smartslider').slider.mainslider.refreshMode();
            });
        }
    });
})(njQuery, window);