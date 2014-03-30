(function ($, scope, undefined) {
    scope.ssAnimationFadestatic = scope.ssAnimationFade.extend({
        timeout: null,
        init: function (layer, options) {
            this._super(layer, options);
            this.options.animate += " smart-slider-animate-fade";
        },
        _stop: function () {
            var $this = this,
                slider = $(this.layer.data('layermanager').slider);
            slider.on('mainanimationend.layerstop', function () {
                if ($this.timeout) clearTimeout($this.timeout);
                $this.layer.stop(true).css('opacity', '0');
                slider.off('mainanimationend.layerstop');
            });
        },
        _setHiddenState: function () {
            this.layer.css('opacity', '1');
        },
        _setInStart: function () {
            this.layer.css('opacity', '0');
        },
        _animateIn: function () {
            this._animate(0, 1, this.options.animate + ' ' + this.options.animateIn, this.options.intervalIn, this.options.easingIn, this.options.delayIn, 'onAnimateInEnd');
        },
        _setOutStart: function () {
            this.layer.css('display', 'block').css('opacity', '1');
        },
        _animateOut: function () {
            this._animate(1, 0, this.options.animate + ' ' + this.options.animateOut, this.options.intervalOut, this.options.easingOut, this.options.delayOut, 'onAnimateOutEnd');
        },
        _animate: function (startOpacity, endOpacity, cssclass, interval, easing, delay, endfn) {
            this.endFN = endfn;
            if (this.timeout) clearTimeout(this.timeout);
            var $this = this;

            this.layer.addClass(cssclass).css('opacity', startOpacity).css('left', 0).css('top', 0);

            this.timeout = setTimeout(function () {
                $this.layer.animate({
                    opacity: endOpacity
                }, {
                    duration: interval,
                    complete: function () {
                        $this.layer.removeClass(cssclass);
                        $this[endfn]();
                    }
                });
            }, 50 + delay);
        }
    });

    scope.ssAnimationManager.addAnimation('fadestatic', scope.ssAnimationFadestatic, {});

})(njQuery, window);