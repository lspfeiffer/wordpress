(function ($, scope, undefined) {
    scope.ssAnimationSlideStatic = scope.ssAnimationSlide.extend({
        timeout: null,
        delayfnstring: '',
        init: function (layer, options) {
            var _this = this;
            if (!options.target) options.target = {};
            this._super(layer, options);
            this.options.animate += " smart-slider-animate-slide";
            var layermanager = $(this.layer).data('layermanager');
            if (layermanager) {
                $(layermanager.slider).on('resize', function (e, ratio, width, height) {
                    _this.onResize(ratio, width, height);
                });
            }
        },
        _stop: function () {
            var $this = this,
                slider = $(this.layer.data('layermanager').slider);
            window[$this.delayfnstring] = null;
            try {
                delete window[$this.delayfnstring];
            } catch (e) {
            }
            if (this.timeout) clearTimeout(this.timeout);
            slider.on('mainanimationend.layerstop', function () {
                $this.layer.stop(true);
                slider.off('mainanimationend.layerstop');
            });
        },
        refreshPosition: function(){
        
        },
        _setInStart: function () {
            var coords = this.getCoords(this.options.mode, this.options.parallaxIn, false);
            this.layer.css('visibility', 'hidden').css('left', coords.origX).css('top', coords.origY);
        },
        _animateIn: function () {
            this._animate(this.getCoords(this.options.mode, this.options.parallaxIn, false), this.options.animate + ' ' + this.options.animateIn, this.options.intervalIn, this.options.easingIn, this.options.delayIn, 'onAnimateInEnd');
        },
        _setOutStart: function () {
            this.layer.css('left', '0%').css('top', '0%');
        },
        _animateOut: function () {
            this._animate(this.getCoords(this.options.mode, this.options.parallaxOut, true), this.options.animate + ' ' + this.options.animateOut, this.options.intervalOut, this.options.easingOut, this.options.delayOut, 'onAnimateOutEnd');
        },
        _animate: function (coords, cssclass, interval, easing, delay, endfn) {
            this.endFN = endfn;
            var $this = this,
                options = this.options;
            this.layer.addClass(cssclass).css('left', coords.origX).css('top', coords.origY).css('opacity', 1);

            var target = {};
            $.extend(target, this.options.target);
            if (coords.targetX !== null) target.left = coords.targetX;
            if (coords.targetY !== null) target.top = coords.targetY;


            if (typeof $.easing[easing] != 'function') easing = 'linear';

            var delay = 50 + delay,
                delaystring = 'sstimer' + delay,
                delayfnstring = delaystring + 'fns';

            this.delayfnstring = delayfnstring;
            if (!window[delayfnstring]) window[delayfnstring] = [];
            window[delayfnstring].push(function () {
                $this.layer.animate(target, {
                    duration: interval,
                    easing: easing,
                    complete: function () {
                        $this.layer.removeClass(cssclass);
                        if(typeof target.left != 'undefined' && target.left != 0) $this.layer.css('left', '-1000%');
                        if(typeof target.top != 'undefined' && target.top != 0) $this.layer.css('left', '-1000%');
                        $this[endfn]();
                    }
                });
            });

            if (window[delaystring]) clearTimeout(window[delaystring]);
            this.timeout = window[delaystring] = setTimeout(function () {
                for (var i = 0; i < window[delayfnstring].length; i++) {
                    window[delayfnstring][i]();
                }
                window[delayfnstring] = null;
                try {
                    delete window[delayfnstring];
                } catch (e) {
                }
            }, delay);
        }
    });

    scope.ssAnimationManager.addAnimation('slidestaticlefttoright', scope.ssAnimationSlideStatic, {
        mode: 'lefttoright'
    });

    scope.ssAnimationManager.addAnimation('slidestaticrighttoleft', scope.ssAnimationSlideStatic, {
        mode: 'righttoleft'
    });

    scope.ssAnimationManager.addAnimation('slidestatictoptobottom', scope.ssAnimationSlideStatic, {
        mode: 'toptobottom'
    });

    scope.ssAnimationManager.addAnimation('slidestaticbottomtotop', scope.ssAnimationSlideStatic, {
        mode: 'bottomtotop'
    });

})(njQuery, window);