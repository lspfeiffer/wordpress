(function ($, scope, undefined) {
    scope.ssAnimationTransit = scope.ssAnimation.extend({
        timeout: null,
        init: function (layer, options) {
            var _this = this;
            //$.transit.useTransitionEnd = true;
            this._super(layer, options);
            this.options.animate += " smart-slider-animate-slide";

        },
        reset: function () {
            if (this.options.reset) {
                this.layer.css(this.options.reset);
            }
        },
        _stop: function () {
            if (this.timeout) clearTimeout(this.timeout);
            var $this = this,
                slider = $(this.layer.data('layermanager').slider);
            slider.on('mainanimationend.layerstop', function () {
                $this.layer.transitionStop(true).css('display', 'none');
                slider.off('mainanimationend.layerstop');
            });
        },
        _setInStart: function () {
            this.layer.css('visibility', 'hidden').css(this.options.startCSS);
        },
        _animateIn: function () {
            this._animate(this.options.animationin, $.extend({}, this.options.startCSS), $.extend({}, this.options.endCSS), 'hidden', 'block', 'block', this.options.animate + ' ' + this.options.animateIn, this.options.intervalIn, this.options.easingIn, this.options.delayIn, this.options.parallaxIn, 'onAnimateInEnd');
        },
        _setOutStart: function () {
            var endcss = $.extend({}, this.options.endCSS);
            if (this.options.parallax) {
                for (var i = 0; i < this.options.parallax.length; i++) {
                    var prop = this.options.parallax[i];
                    endcss[prop] *= this.options.parallaxOut;
                }
            }
            this.layer.css(endcss).css('display', 'block');
        },
        _animateOut: function () {
            this._animate(this.options.animationout, $.extend({}, this.options.endCSS), $.extend({}, this.options.startCSS), 'visible', 'block', 'block', this.options.animate + ' ' + this.options.animateOut, this.options.intervalOut, this.options.easingOut, this.options.delayOut, this.options.parallaxOut, 'onAnimateOutEnd');
        },
        _animate: function (animation, startcss, endcss, startVisibility, startDisplay, endDisplay, cssclass, interval, easing, delay, parallax, endfn) {
            this.endFN = endfn;
            var $this = this,
                options = this.options;
            var left = this.layer[0].origLeftPercent / 100 * options.width;
            var top = this.layer[0].origTopcent / 100 * options.height;

            if (this.options.parallax) {
                for (var i = 0; i < this.options.parallax.length; i++) {
                    var prop = this.options.parallax[i];
                    startcss[prop] *= parallax;
                    endcss[prop] *= parallax;
                }
            }

            this.layer.addClass(cssclass).css('visibility', startVisibility).css(startcss).css('display', startDisplay);


            if (typeof $.easing[easing] != 'function') easing = 'linear';

            this.timeout = setTimeout(function () {
                var layer = $this.layer,
                    percent = 0;
                if (nModernizr.csstransitions && animation && animation.length > 0) {
                    for (var i = 0; i < animation.length; i++) {
                        layer.css('visibility', 'visible').transition(
                            animation[i].css,
                            interval * (animation[i].percent - percent) / 100,
                            easing
                        );
                        percent = animation[i].percent;
                    }
                }
                layer.data('sstransit', endfn);
                layer.css('visibility', 'visible').transition(
                    endcss,
                    interval * (100 - percent) / 100,
                    easing,
                    function () {
                        $this.layer.css('display', endDisplay).removeClass(cssclass);
                        $this[endfn]();
                    }
                );
            }, 50 + parseInt(delay));

        }
    });

    scope.ssAnimationManager.addAnimation('flipx', scope.ssAnimationTransit, {
        startCSS: {
            opacity: 0,
            perspective: '400px',
            rotateX: 90
        },
        animationin: [
            {
                percent: 40,
                css: {
                    opacity: 0.4,
                    rotateX: -10
                }
            },
            {
                percent: 70,
                css: {
                    opacity: 0.7,
                    rotateX: 10
                }
            }
        ],
        endCSS: {
            opacity: 1,
            rotateX: 0
        }
    });

    scope.ssAnimationManager.addAnimation('flipy', scope.ssAnimationTransit, {
        startCSS: {
            opacity: 0,
            perspective: '400px',
            rotateY: 90
        },
        animationin: [
            {
                percent: 40,
                css: {
                    opacity: 0.4,
                    rotateY: -10
                }
            },
            {
                percent: 70,
                css: {
                    opacity: 0.7,
                    rotateY: 10
                }
            }
        ],
        endCSS: {
            opacity: 1,
            rotateY: 0
        }
    });

    scope.ssAnimationManager.addAnimation('fadeup', scope.ssAnimationTransit, {
        startCSS: {
            opacity: 0,
            y: 1000
        },
        endCSS: {
            opacity: 1,
            y: 0
        },
        parallax: ['y']
    });

    scope.ssAnimationManager.addAnimation('faderight', scope.ssAnimationTransit, {
        startCSS: {
            opacity: 0,
            x: 1000
        },
        endCSS: {
            opacity: 1,
            x: 0
        },
        parallax: ['x']
    });

    scope.ssAnimationManager.addAnimation('fadedown', scope.ssAnimationTransit, {
        startCSS: {
            opacity: 0,
            y: -1000
        },
        endCSS: {
            opacity: 1,
            y: 0
        },
        parallax: ['y']
    });

    scope.ssAnimationManager.addAnimation('fadeleft', scope.ssAnimationTransit, {
        startCSS: {
            opacity: 0,
            x: -1000
        },
        endCSS: {
            opacity: 1,
            x: 0
        },
        parallax: ['x']
    });

    scope.ssAnimationManager.addAnimation('bounce', scope.ssAnimationTransit, {
        startCSS: {
            opacity: 0,
            scale: 0
        },
        animationin: [
            {
                percent: 50,
                css: {
                    opacity: 1,
                    scale: 1.05
                }
            },
            {
                percent: 70,
                css: {
                    scale: 0.9
                }
            }
        ],
        endCSS: {
            opacity: 1,
            scale: 1
        }
    });

    scope.ssAnimationManager.addAnimation('rotate', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'center center',
            rotate: -360
        },
        endCSS: {
            transformOrigin: 'center center',
            rotate: 0
        },
        parallax: ['rotate']
    });

    scope.ssAnimationManager.addAnimation('rotateupleft', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'left bottom',
            rotate: 90
        },
        endCSS: {
            transformOrigin: 'left bottom',
            rotate: 0
        },
        parallax: ['rotate']
    });

    scope.ssAnimationManager.addAnimation('rotatedownleft', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'left bottom',
            rotate: -90
        },
        endCSS: {
            transformOrigin: 'left bottom',
            rotate: 0
        },
        parallax: ['rotate']
    });

    scope.ssAnimationManager.addAnimation('rotateupright', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'right bottom',
            rotate: 90
        },
        endCSS: {
            transformOrigin: 'right bottom',
            rotate: 0
        },
        parallax: ['rotate']
    });

    scope.ssAnimationManager.addAnimation('rotatedownright', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'right bottom',
            rotate: -90
        },
        endCSS: {
            transformOrigin: 'right bottom',
            rotate: 0
        },
        parallax: ['rotate']
    });

    scope.ssAnimationManager.addAnimation('rollin', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'center center',
            opacity: 0,
            x: '-100%',
            rotate: -360
        },
        endCSS: {
            transformOrigin: 'center center',
            opacity: 1,
            x: 0,
            rotate: 0
        },
        parallax: ['rotate']
    });

    scope.ssAnimationManager.addAnimation('rollout', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'center center',
            opacity: 0,
            x: '100%',
            rotate: 360
        },
        endCSS: {
            transformOrigin: 'center center',
            opacity: 1,
            x: 0,
            rotate: 0
        },
        parallax: ['rotate']
    });

    scope.ssAnimationManager.addAnimation('scale', scope.ssAnimationTransit, {
        startCSS: {
            transformOrigin: 'center center',
            scale: 0
        },
        endCSS: {
            transformOrigin: 'center center',
            scale: 1
        }
    });

})(njQuery, window);