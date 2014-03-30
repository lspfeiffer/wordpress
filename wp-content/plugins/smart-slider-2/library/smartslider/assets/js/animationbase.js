(function ($, scope, undefined) {
    scope.ssAnimation = NClass.extend({
        _outplayed: false,
        endFN: null,
        endFired: true,
        init: function (layer, options) {
            var _this = this;
            this.layer = layer;
            this.canvas = $(this.layer).data('slide');
            if (!this.canvas)
                this.canvas = this.layer;
            if (this.layer.animated === undefined)
                this.layer.animated = false;
            this.options = $.extend({
                easingIn: 'linear',
                easingOut: 'linear',
                intervalIn: 400,
                intervalOut: 400,
                delayIn: 0,
                delayOut: 0,
                parallaxIn: 0.45,
                parallaxOut: 0.45,
                animate: "smart-slider-animate",
                animateIn: "smart-slider-animate-in",
                animateOut: "smart-slider-animate-out",
                endFn: function () {
                }
            }, options);
        },
        _initAnimation: function () {
            var $layer = this.layer;
            $(this.canvas).trigger('incrementanimation');
            this.layer.on('ssanimateinstart.ssdefault',function (event) {
                event.stopPropagation();
                $layer.off('ssanimateinstart.ssdefault');
            }).on('ssanimateoutstart.ssdefault',function (event) {
                    event.stopPropagation();
                    $layer.off('ssanimateoutstart.ssdefault');
                }).on('ssanimateinend.ssdefault',function (event) {
                    event.stopPropagation();
                    $layer.off('ssanimateinend.ssdefault');
                }).on('ssanimateoutend.ssdefault', function (event) {
                    event.stopPropagation();
                    $layer.off('ssanimateoutend.ssdefault');
                });
        },
        onResize: function (ratio) {

        },
        refreshPosition: function(dim){
        
        },
        setHiddenState: function () {
            if (!this.layer.animated) {
                this._setHiddenState();
            }
        },
        stop: function () {
            if (!this.endFired) {
                this[this.endFN](true);
                this._stop();
                this.endFired = 1;
            }
        },
        _stop: function () {
        },
        _setHiddenState: function () {

        },
        reset: function () {

        },
        setInStart: function () {
            if (!this.layer.animated) {
                this._setInStart();
            }
        },
        _setInStart: function () {
        },
        outPlayed: function (state) {
            if (state === undefined) {
                return this._outplayed;
            }
            this._outplayed = state;
        },
        setOutStart: function () {
            if (!this.layer.animated) {
                this._setOutStart();
            }
        },
        _setOutStart: function () {
            this.layer.css('display', 'block');
        },
        animateIn: function () {
            if (this._canAnimate()) {
                this._initAnimation();
                this.layer.trigger('ssanimateinstart');

                var out = this.layer.data('motionout');
                if (out)
                    out.outPlayed(false);

                this.endFired = 0;
                this._animateIn();
                return true;
            }
            return false;
        },
        onAnimateInEnd: function (forced) {
            if (typeof forced == 'undefined') forced = false;
            if (!this.endFired) {
                this._endAnimate();
                var playoutafter = this.layer.data('playoutafter');
                if (!forced && playoutafter) {
                    var motion = this.layer.data('motionout');
                    motion.animateOut();
                    motion.outPlayed(true);
                } else {
                    this.layer.trigger('ssanimateinend');
                }
                this.endFired = 1;
            }
        },
        animateOut: function () {
            if (this._canAnimate()) {
                this._initAnimation();
                this.layer.trigger('ssanimateoutstart');
                if (this.outPlayed()) {
                    var $this = this;
                    setTimeout(function () {
                        $this.endFired = 0;
                        $this.onAnimateOutEnd();
                    }, 200); // Hack to fire end with some delay if playafterin
                } else {
                    this.endFired = 0;
                    this._animateOut();
                }
                return true;
            }
            return false;
        },
        onAnimateOutEnd: function (forced) {
            if (!this.endFired) {
                this._endAnimate();
                this.layer.trigger('ssanimateoutend');
                this.endFired = 1;
            }
        },
        _canAnimate: function () {
            if (this.layer.animated)
                return false;
            return this.layer.animated = true;
        },
        _endAnimate: function () {
            this.layer.animated = false;
            this.options.endFn();
            $(this.canvas).trigger('decrementanimation');
        }
    });

    scope.ssAnimationManagerClass = NClass.extend({
        init: function () {
            this.animations = {};
        },
        addAnimation: function (name, classdefinition, options) {
            this.animations[name] = {
                classdefinition: classdefinition,
                options: options
            };
        },
        getAnimation: function (name, layer, options) {
            if (this.animations[name] === undefined) {
                name = 'no';
            }
            return new this.animations[name].classdefinition(layer, $.extend(this.animations[name].options, options));
        }
    });
    if (scope.ssAnimationManager === undefined)
        scope.ssAnimationManager = new scope.ssAnimationManagerClass();

})(njQuery, window);