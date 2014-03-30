;
(function ($, scope, undefined) {
    var methods = {
        init: function (options) {
            var settings = $.extend({
            }, options);
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('smartslider');
                if (!data) {
                    var slider = smartsliderbase($this, settings)
                    $(this).data('smartslider', {
                        slider: slider
                    });
                    data = $this.data('smartslider');
                    $this.trigger('inited', [slider]);
                }
            });

        },
        onInit: function(fn){
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('smartslider');
                if(data){
                    fn({}, data.slider);
                }else{
                    $this.on('inited', fn);
                }
            });
        },
        next: function () {
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('smartslider');
                data.slider.next();
            });
        },
        previous: function () {
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('smartslider');
                data.slider.previous();
            });
        },
        goto: function (i, reversed) {
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('smartslider');
                data.slider.goto(i, reversed);
            });
        },
        startautoplay: function () {
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('smartslider');
                data.slider.startautoplay();
            });
        },
        pauseautoplay: function () {
            return this.each(function () {
                var $this = $(this),
                    data = $this.data('smartslider');
                data.slider.pauseautoplay();
            });
        }
    };

    $.fn.extend({
        smartslider: function (method) {
            this.defaultOptions = {};

            var options = $.extend({}, this.defaultOptions, options);

            if (methods[method]) {
                return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
            } else if (typeof method === 'object' || !method) {
                return methods.init.apply(this, arguments);
            } else {
                $.error('Method ' + method + ' does not exist on jQuery.tooltip');
            }
        }
    });

    window.smartslider = {};
    window.smartslider.motions = {};

    window.smartsliderbase = function (el, options) {
        var proto = function (el, options) {
            var $this = this;
            this.$el = el;
            this.options = options;

            this.canvasList = null;

            this.slideAnimateIn = "smart-slider-slide-animate-in";
            this.slideAnimateOut = "smart-slider-slide-animate-out";
            this.slideActive = "smart-slider-slide-active";

            this.mainslider = new scope[options.type](this, el, options);

            this.next = function () {
                this.mainslider.next();
            };

            this.previous = function () {
                this.mainslider.previous();
            };

            this.goto = function (i, reversed) {
                this.mainslider.changeTo(i, reversed);
            };

            this.startautoplay = function () {
                this.mainslider.reStartAutoPlay();
            };

            this.pauseautoplay = function () {
                this.mainslider.pauseAutoPlay();
            };
        };
        return new proto(el, options);
    };

})(njQuery, window);