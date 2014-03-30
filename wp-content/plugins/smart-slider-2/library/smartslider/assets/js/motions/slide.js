(function ($, scope, undefined) {
    scope.ssAnimationSlide = scope.ssAnimation.extend({
        timeout: null,
        delayfnstring: '',
        init: function (layer, options) {
            var _this = this;
            if (!options.target) options.target = {};
            this._super(layer, options);
            this.options.animate += " smart-slider-animate-slide";

            var l = layer[0];
            l.origLeftPercent = parseFloat(l.style.left);
            l.origTopcent = parseFloat(l.style.top);
            if (!l.origLeftPercent) l.origLeftPercent = 0;
            if (!l.origTopcent) l.origTopcent = 0;

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
                $this.layer.css('display', 'none').stop(true);
                slider.off('mainanimationend.layerstop');
            });
        },
        onResize: function (ratio, width, height) {
            this.options.width = width;
            this.options.height = height;
        },
        refreshPosition: function(dim){
            var l = this.layer[0];
            l.origLeftPercent = parseFloat(dim.left);
            l.origTopcent = parseFloat(dim.top);
        },
        _setInStart: function () {
            var coords = this.getCoords(this.options.mode, this.options.parallaxIn, false);
            var left = this.layer[0].origLeftPercent / 100 * this.options.width;
            var top = this.layer[0].origTopcent / 100 * this.options.height;
            this.layer.css('visibility', 'hidden')
            .css('left', left + coords.origX)
                .css('top', top + coords.origY);
        },
        _animateIn: function () {
            this._animate(this.getCoords(this.options.mode, this.options.parallaxIn, false), 'hidden', 'block', 'block', this.options.animate + ' ' + this.options.animateIn, this.options.intervalIn, this.options.easingIn, this.options.delayIn, 'onAnimateInEnd');
        },
        _setOutStart: function () {
            this.layer.css('left', this.layer[0].origLeftPercent + '%')
                .css('top', this.layer[0].origTopcent + '%')
                .css('display', 'block');
        },
        _animateOut: function () {
            this._animate(this.getCoords(this.options.mode, this.options.parallaxOut, true), 'visible', 'block', 'none', this.options.animate + ' ' + this.options.animateOut, this.options.intervalOut, this.options.easingOut, this.options.delayOut, 'onAnimateOutEnd');
        },
        _animate: function (coords, startVisibility, startDisplay, endDisplay, cssclass, interval, easing, delay, endfn) {
            this.endFN = endfn;
            var $this = this,
                options = this.options;
            var left = this.layer[0].origLeftPercent / 100 * options.width;
            var top = this.layer[0].origTopcent / 100 * options.height;
            this.layer.addClass(cssclass).css('left', left + coords.origX).css('top', top + coords.origY).css('visibility', startVisibility).css('display', startDisplay);

            var target = {};
            $.extend(target, this.options.target);
            if (coords.targetX !== null) target.left = left + coords.targetX;
            if (coords.targetY !== null) target.top = top + coords.targetY;


            if (typeof $.easing[easing] != 'function') easing = 'linear';

            var delay = 50 + delay,
                delaystring = 'sstimer' + delay,
                delayfnstring = delaystring + 'fns';

            this.delayfnstring = delayfnstring;
            if (!window[delayfnstring]) window[delayfnstring] = [];
            window[delayfnstring].push(function () {
                $this.layer.css('visibility', 'visible').animate(target, {
                    duration: interval,
                    easing: easing,
                    complete: function () {
                        $this.layer.css('display', endDisplay).removeClass(cssclass).css('left', $this.layer[0].origLeftPercent + '%').css('top', $this.layer[0].origTopcent + '%');
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
        },
        getCoords: function (direction, parallax, out) {
            var coords = {
                targetX: null,
                targetY: null,
                origX: 0,
                origY: 0
            }, options = this.options;
            if (out) {
                switch (direction) {
                    case 'righttoleft':
                        coords.origX = 0;
                        coords.targetX = -1 * options.width * parallax;
                        break;
                    case 'lefttoright':
                        coords.origX = 0;
                        coords.targetX = options.width * parallax;
                        break;
                    case 'toptobottom':
                        coords.origY = 0;
                        coords.targetY = options.height * parallax;
                        break;
                    case 'bottomtotop':
                        coords.origY = 0;
                        coords.targetY = -1 * options.height * parallax;
                        break;
                    default:
                }
            } else {
                switch (direction) {
                    case 'righttoleft':
                        coords.origX = options.width * parallax;
                        coords.targetX = 0;
                        break;
                    case 'lefttoright':
                        coords.origX = -1 * options.width * parallax;
                        coords.targetX = 0;
                        break;
                    case 'toptobottom':
                        coords.origY = -1 * options.height * parallax;
                        coords.targetY = 0;
                        break;
                    case 'bottomtotop':
                        coords.origY = options.height * parallax;
                        coords.targetY = 0;
                        break;
                    default:
                }
            }
            return coords;
        }
    });

    scope.ssAnimationManager.addAnimation('slidelefttoright', scope.ssAnimationSlide, {
        mode: 'lefttoright'
    });

    scope.ssAnimationManager.addAnimation('sliderighttoleft', scope.ssAnimationSlide, {
        mode: 'righttoleft'
    });

    scope.ssAnimationManager.addAnimation('slidetoptobottom', scope.ssAnimationSlide, {
        mode: 'toptobottom'
    });

    scope.ssAnimationManager.addAnimation('slidebottomtotop', scope.ssAnimationSlide, {
        mode: 'bottomtotop'
    });

})(njQuery, window);