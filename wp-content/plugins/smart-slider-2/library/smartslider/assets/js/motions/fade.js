(function ($, scope, undefined) {
    scope.ssAnimationFade = scope.ssAnimation.extend({
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
                $this.layer.stop(true).css('display', 'none');
                slider.off('mainanimationend.layerstop');
            });
        },
        _setHiddenState: function () {
            this.layer.css('opacity', '1');
        },
        _setInStart: function () {
            this.layer.css('display', 'none');
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
            var endDisplay = (endOpacity === 0) ? 'none' : 'block';

            this.layer.addClass(cssclass).css('opacity', startOpacity).css('display', 'block');

            this.timeout = setTimeout(function () {
                $this.layer.animate({
                    opacity: endOpacity
                }, {
                    duration: interval,
                    complete: function () {
                        $this.layer.css('display', endDisplay).removeClass(cssclass);
                        $this[endfn]();
                    }
                });
            }, 50 + delay);
        }
    });

    scope.ssAnimationManager.addAnimation('fade', scope.ssAnimationFade, {});

})(njQuery, window);