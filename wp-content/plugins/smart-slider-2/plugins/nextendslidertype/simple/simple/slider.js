(function ($, scope, undefined) {
    scope.ssSimpleSlider = scope.ssTypeBase.extend({
        extraParallax: 1,
        init: function (parent, $el, options) {
            var _this = this;

            options.flux[0] = (options.flux[0] && parseInt(options.flux[0])) ? true : false;

            this._super(parent, $el, options);
        },
        afterInit: function(){
            var _this = this;
            this._super();
            this.smartsliderborder2 = this.$slider.find('.smart-slider-border2');
            this.slideList.not(this.slideList.eq(this._active)).css('left', '-1000%');

            this.$this.on('mainanimationoutend', function () {
                var $slide = this.slideList.eq(_this._lastActive);
                //$slide.css('display', 'none');
            });
            $(this).on('load.firstsub', function () {
                $(this).off('load.firstsub');
            });
        },
        sizeInited: function () {
            if(njQuery('#'+this.id+' .nextend-flux img').length != this.slideList.length) this.options.flux[0] = 0;
            if (this.options.flux[0]) {
                this.flux = new flux.slider('#'+this.id+' .nextend-flux', {
                    transitions: this.options.flux[1],
                    width: this.slideDimension.w,
                    height: this.slideDimension.h,
                    currentImageIndex: this._active,
                    nextImageIndex: this._active + 1
                });
            }
        },
        storeDefaults: function () {
            var _this = this,
                ss = this.$slider;

            ss.data('ss-outerwidth', ss.outerWidth(true));

            //ss.data('ss-fontsize', parseInt(ss.css('fontSize')));

            this.variables.margintop = parseInt(ss.css('marginTop'));
            this.variables.marginright = parseInt(ss.css('marginRight'));
            this.variables.marginbottom = parseInt(ss.css('marginBottom'));
            this.variables.marginleft = parseInt(ss.css('marginLeft'));
            
            ss.data('ss-m-t', this.variables.margintop);
            ss.data('ss-m-r', this.variables.marginright);
            ss.data('ss-m-b', this.variables.marginbottom);
            ss.data('ss-m-l', this.variables.marginleft);
            
            this.variables.outerwidth = ss.parent().width();
            this.variables.outerheight = ss.parent().height();
                
            this.variables.width = ss.width();
            this.variables.height = ss.height();
            
            ss.data('ss-w', this.variables.width);
            ss.data('ss-h', this.variables.height);

            var smartsliderborder1 = this.smartsliderborder1 = ss.find('.smart-slider-border1');
            
            smartsliderborder1.data('ss-w', smartsliderborder1.width());
            smartsliderborder1.data('ss-h', smartsliderborder1.height());
            smartsliderborder1.data('ss-p-t', parseInt(smartsliderborder1.css('paddingTop')));
            smartsliderborder1.data('ss-p-r', parseInt(smartsliderborder1.css('paddingRight')));
            smartsliderborder1.data('ss-p-b', parseInt(smartsliderborder1.css('paddingBottom')));
            smartsliderborder1.data('ss-p-l', parseInt(smartsliderborder1.css('paddingLeft')));

            var canvases = this.smartslidercanvasinner = this.slideList.find('.smart-slider-canvas-inner');
                
            this.variables.canvaswidth = canvases.width();
            this.variables.canvasheight = canvases.height();
            
            canvases.data('ss-w', this.variables.canvaswidth);
            canvases.data('ss-h', this.variables.canvasheight);
            
            this.slideList.css({
                width: this.variables.canvaswidth,
                height: this.variables.canvasheight
            });
            
            this.imagesinited = false;
            this.$slider.waitForImages(function () {
                $.each(_this.slidebgList, function(){
                    var $img = $(this);
                    var im = $("<img/>").attr("src", $img.attr("src"));
                    $img.data('ss-w', im[0].width < 10 ? _this.variables.canvaswidth : im[0].width);
                    $img.data('ss-h', im[0].height < 10 ? _this.variables.canvasheight : im[0].height);
                });
                _this.imagesinited = true;
                _this.$slider.trigger('imagesinited');
            });

            this.variablesRefreshed();
        },
        onResize: function (fixedratio) {
            var _this = this,
                ss = this.$slider;
                
            var modechanged = this.refreshMode(); //this._currentmode

            var ratio = 1;

            var availableWidth = ss.parent().width();

            var outerWidth = ss.data('ss-outerwidth');

            if (!this.options.responsive.upscale && availableWidth > outerWidth) availableWidth = outerWidth;
            
            if(typeof fixedratio == 'undefined'){
                if (availableWidth != outerWidth) {
                    ratio = availableWidth / outerWidth;
                }
    
                if (!modechanged && (this.lastAvailableWidth == availableWidth || !this.options.responsive.downscale && ratio < 1)) {
                    var _this = this;
                    this.$slider.waitForImages(function () {
                        $(_this).trigger('load');
                    });
                    return true;
                }
            }else{
                ratio = fixedratio; 
            }

            this.lastAvailableWidth = availableWidth;

            ss.css('fontSize', ss.data(this._currentmode+'fontsize') * ratio + 'px');

            this.variables.margintop = parseInt(ss.data('ss-m-t') * ratio);
            this.variables.marginright = parseInt(ss.data('ss-m-r') * ratio);
            this.variables.marginbottom = parseInt(ss.data('ss-m-b') * ratio);
            this.variables.marginleft = parseInt(ss.data('ss-m-l') * ratio);

            ss.css('marginTop', this.variables.margintop);
            ss.css('marginRight', this.variables.marginright);
            ss.css('marginBottom', this.variables.marginbottom);
            ss.css('marginLeft', this.variables.marginleft);

            var smartsliderborder1 = this.smartsliderborder1;


            smartsliderborder1.css('paddingTop', parseInt(smartsliderborder1.data('ss-p-t') * ratio) + 'px');
            smartsliderborder1.css('paddingRight', parseInt(smartsliderborder1.data('ss-p-r') * ratio) + 'px');
            smartsliderborder1.css('paddingBottom', parseInt(smartsliderborder1.data('ss-p-b') * ratio) + 'px');
            smartsliderborder1.css('paddingLeft', parseInt(smartsliderborder1.data('ss-p-l') * ratio) + 'px');

            smartsliderborder1.width(parseInt(smartsliderborder1.data('ss-w') * ratio));


            this.variables.width = smartsliderborder1.outerWidth(true);
            ss.width(this.variables.width);


            var canvases = this.smartslidercanvasinner;
            var oCanvasWidth = canvasWidth = parseInt(canvases.data('ss-w') * ratio),
                oCanvasHeight = parseInt(canvases.data('ss-h') * ratio),
                margin = 0,
                maxw = this.options.responsive.maxwidth,
                ratio2 = ratio;

            if (canvasWidth > this.options.responsive.maxwidth) {
                margin = parseInt((canvasWidth - maxw) / 2);
                ratio2 = maxw / canvases.data('ss-w');
                canvasWidth = parseInt(canvases.data('ss-w') * ratio2);
            }

            this.extraParallax = ratio / ratio2;

            var canvasHeight = parseInt(canvases.data('ss-h') * ratio2);
            
            if (this.options.flux[0]) this.flux.changeSize(oCanvasWidth, canvasHeight);

            canvases.width(canvasWidth).height(canvasHeight).css({
                marginLeft: margin,
                marginRight: margin
            });

            this.slideList.css({
                width: canvases.outerWidth(true),
                height: canvases.outerHeight(true)
            });

            smartsliderborder1.css('fontSize', ss.data(this._currentmode+'fontsize') * ratio2 + 'px');

            smartsliderborder1.height(canvasHeight);
            
            this.variables.height = smartsliderborder1.outerHeight(true);
            ss.height(this.variables.height);

            this.slideDimension.w = canvasWidth;
            this.slideDimension.h = canvasHeight;

            this.variables.canvaswidth = canvasWidth;
            this.variables.canvasheight = canvasHeight;
            
            
            this.variables.outerwidth = ss.parent().width();
            this.variables.outerheight = ss.parent().height();
            
            
            this.slidebgList.width(oCanvasWidth);
            var bgfn = function () {
                $.each(_this.slidebgList, function(){
                    var $img = $(this);
                    $img.height(parseInt(oCanvasWidth/$img.data('ss-w')*$img.data('ss-h')));
                });
            };
            if(_this.imagesinited){
                bgfn();
            }else{
                _this.$slider.on('imagesinited', function(){
                    bgfn();
                });
            }


            for (var i = 0; i < window[this.id + '-onresize'].length; i++) {
                window[this.id + '-onresize'][i](ratio);
            }
            $(this).trigger('resize', [ratio, canvasWidth, canvasHeight]);

            var _this = this;
            this.$slider.waitForImages(function () {
                $(_this).trigger('load');
            });
            
            this.variablesRefreshed();
        },
        animateOut: function (i, reversed) {
            var _this = this;
            this._lastActive = i;

            this.initAnimation();

            var $slide = this.slideList.eq(i);
            $slide.on('ssanimationsended.ssmainanimateout',function () {
                $slide.off('ssanimationsended.ssmainanimateout');
                _this.$this.trigger('mainanimationoutend');
                _this.mainanimationended();
            }).trigger('ssoutanimationstart');
            this.__animateOut($slide, reversed).animateOut();
        },
        animateIn: function (i, reversed) {
            this._active = i;
            var _this = this,
                $slide = this.slideList.eq(i);

            $slide.width(this.slideList.width());
            $slide.on('ssanimationsended.ssmainanimatein',function () {
                $slide.off('ssanimationsended.ssmainanimatein');
                _this.$this.trigger('mainanimationinend');
                _this.mainanimationended();
            }).trigger('ssinanimationstart');

            if (this.options.flux[0]) {
                //make them synced
                var ended = null,
                endFN = function(){
                    _this.mainanimationended();
                    $slide.trigger('decrementanimation');
                };
                ended = function(){
                    ended = endFN;
                };
                
                $slide.trigger('incrementanimation');
                this.__animateIn($slide, reversed,function () {
                    ended();
                }).animateIn();
                this.flux.element.on('fluxTransitionEnd.ss', function (event) {
                    $(this).off('fluxTransitionEnd.ss');
                    ended();
                });
                this.flux.showImage(i);
            } else {
                this.__animateIn($slide, reversed,function () {
                    _this.mainanimationended();
                }).animateIn();
            }
        },

        initAnimation: function () {
            var currentAnimation = this.options.animation[Math.floor(Math.random() * this.options.animation.length)];
            this._animationOptions = {
                next: {},
                current: {}
            };

            this._animationOptions.next = $.merge(this.options.animationSettings, this._animationOptions.next);
            this._animationOptions.current = $.merge(this.options.animationSettings, this._animationOptions.current);

            switch (currentAnimation) {
                case 'horizontal':
                    this.__animateIn = this.__animateInHorizontal;
                    this.__animateOut = this.__animateOutHorizontal;
                    break;
                case 'vertical':
                    this.__animateIn = this.__animateInVertical;
                    this.__animateOut = this.__animateOutVertical;
                    break;
                case 'fade':
                    this.__animateIn = this.__animateInFade;
                    this.__animateOut = this.__animateOutFade;
                    break;
                default:
                    this.__animateIn = this.__animateInNo;
                    this.__animateOut = this.__animateOutNo;
                    break;
            }
        },

        __animateIn: function ($slide, reversed, end) {

        },

        __animateOut: function ($slide, reversed, end) {

        },

        __animateInNo: function ($slide, reversed, end) {
            if (end) end();
            return ssAnimationManager.getAnimation('no', $slide, {});
        },

        __animateOutNo: function ($slide, reversed, end) {
            if (end) end();
            return ssAnimationManager.getAnimation('no', $slide, {});
        },

        __animateInHorizontal: function ($slide, reversed, end) {

            var option = this._animationOptions.next;
            return ssAnimationManager.getAnimation((reversed && option.parallax >= 1) ? 'slidestaticlefttoright' : 'slidestaticrighttoleft', $slide, {
                width: this.slideDimension.w,
                height: this.slideDimension.h,
                intervalIn: option.duration,
                easingIn: option.easing,
                delayIn: option.delay,
                parallaxIn: option.parallax * this.extraParallax,
                target: {},
                endFn: function () {
                    if (end) end();
                }
            });
        },

        __animateOutHorizontal: function ($slide, reversed, end) {

            var _this = this,
                option = this._animationOptions.current,
                target = option.parallax < 1 ? {width: this.smartsliderborder2.width() * option.parallax} : {};

            return ssAnimationManager.getAnimation((reversed && option.parallax >= 1) ? 'slidestaticlefttoright' : 'slidestaticrighttoleft', $slide, {
                width: this.slideDimension.w,
                height: this.slideDimension.h,
                intervalOut: option.duration,
                easingOut: option.easing,
                delayOut: option.delay,
                parallaxOut: option.parallax * this.extraParallax,
                target: target,
                endFn: function () {
                    $slide.width(_this.smartsliderborder2.width());
                    if (end) end();
                }
            });
        },

        __animateInVertical: function ($slide, reversed, end) {

            var option = this._animationOptions.next;
            return ssAnimationManager.getAnimation((reversed && option.parallax >= 1) ? 'slidestatictoptobottom' : 'slidestaticbottomtotop', $slide, {
                width: this.slideDimension.w,
                height: this.slideDimension.h,
                intervalIn: option.duration,
                easingIn: option.easing,
                delayIn: option.delay,
                parallaxIn: option.parallax * this.extraParallax,
                target: {},
                endFn: function () {
                    if (end) end();
                }
            });
        },

        __animateOutVertical: function ($slide, reversed, end) {

            var _this = this,
                option = this._animationOptions.current,
                target = option.parallax < 1 ? {height: this.smartsliderborder2.height() * option.parallax} : {};

            return ssAnimationManager.getAnimation((reversed && option.parallax >= 1) ? 'slidestatictoptobottom' : 'slidestaticbottomtotop', $slide, {
                width: this.slideDimension.w,
                height: this.slideDimension.h,
                intervalOut: option.duration,
                easingOut: option.easing,
                delayOut: option.delay,
                parallaxOut: option.parallax * this.extraParallax,
                target: target,
                endFn: function () {
                    $slide.height(_this.smartsliderborder2.height());
                    if (end) end();
                }
            });
        },

        __animateInFade: function ($slide, reversed, end) {

            var option = this._animationOptions.next;
            return ssAnimationManager.getAnimation('fadestatic', $slide, {
                width: this.slideDimension.w,
                height: this.slideDimension.h,
                intervalIn: option.duration,
                easingIn: option.easing,
                delayIn: option.delay,
                parallaxIn: option.parallax * this.extraParallax,
                endFn: function () {
                    if (end) end();
                }
            });
        },

        __animateOutFade: function ($slide, reversed, end) {

            var option = this._animationOptions.current;

            return ssAnimationManager.getAnimation('fadestatic', $slide, {
                width: this.slideDimension.w,
                height: this.slideDimension.h,
                intervalOut: option.duration,
                easingOut: option.easing,
                delayOut: option.delay,
                parallaxOut: option.parallax * this.extraParallax,
                endFn: function () {
                    if (end) end();
                }
            });
        },
        initTouch: function () {
            if((this.options.touchanimation != 'horizontal' && this.options.touchanimation != 'vertical') || (typeof jQuery != 'undefined' && typeof jQuery.UIkit != 'undefined')){
                this._super();
                return;
            }
            
            var _this = this;
            var mode = this.options.touchanimation,
                reset = [];
            
            this.$slider.find('> div').eq(0).swipe({
                tap: function(event, target) {
                    var act = _this.slideList.eq(_this._active).trigger('click');
                    if(typeof act.attr("onclick") != undefined){
                        event.preventDefault();
                        event.stopPropagation();
                    }
                },
                swipe: function (event, direction, distance, duration, fingerCount) {
                    if(_this._animating) return;
                    if(_this.options.touchanimation == 'horizontal'){
                        _this.__animateHorizontalTouch(direction);
                    }else if(_this.options.touchanimation == 'vertical'){
                        _this.__animateVerticalTouch(direction);
                    }
                },
                swipeStatus:function(event, phase, direction, distance, duration, fingers){
                    if(_this._animating) return;
                    var active = _this._active,
                        next = null;
                    
                    if(_this.options.touchanimation == 'horizontal'){
                        if(direction == 'left'){
                            next = active + 1;
                            if (next === _this.slideList.length) next = 0;
                            _this.slideList.eq(active).css('left', -distance);
                            _this.slideList.eq(next).css('left', _this.slideDimension.w-distance)/*.css('display', 'block')*/;
                        }else if(direction == 'right'){
                            next = active - 1;
                            if (next < 0) next = _this.slideList.length - 1;
                            _this.slideList.eq(active).css('left', distance);
                            _this.slideList.eq(next).css('left', -_this.slideDimension.w+distance)/*.css('display', 'block')*/;
                        }
                        
                        if(phase=="end"){
                            reset = [];
                            if(distance < 75){
                                _this.slideList.eq(active).css('left', 0);
                                if(next !== null) _this.slideList.eq(next).css('left', '-1000%');
                            }
                        }
                    }else if(_this.options.touchanimation == 'vertical'){
                        if(direction == 'up'){
                            next = active + 1;
                            if (next === _this.slideList.length) next = 0;
                            _this.slideList.eq(active).css('top', -distance);
                            _this.slideList.eq(next).css('top', _this.slideDimension.h-distance).css('left', '0')/*.css('display', 'block')*/;
                        }else if(direction == 'down'){
                            next = active - 1;
                            if (next < 0) next = _this.slideList.length - 1;
                            _this.slideList.eq(active).css('top', distance);
                            _this.slideList.eq(next).css('top', -_this.slideDimension.h+distance).css('left', '0')/*.css('display', 'block')*/;
                        }
                        
                        if(phase=="end"){
                            reset = [];
                            if(distance < 75){
                                _this.slideList.eq(active).css('top', 0);
                                if(next !== null) _this.slideList.eq(next).css('left', '-1000%')/*.css('display', 'none')*/;
                            }
                        }
                    }
                    if(next !== null && typeof reset[next] == 'undefined'){
                        _this.slideList.eq(next).trigger('ssanimatelayerssetinstart');
                        reset[next] = true;
                    }
                },
                fallbackToMouseEvents: false,
                allowPageScroll: (_this.options.touchanimation == 'horizontal' ? 'vertical' : 'horizontal')
            });
            
            if(typeof window.MSGesture !== 'undefined'){
                var gesture = new MSGesture(),
                    el = this.$slider.find('> div').get(0),
                    start = {
                        x: 0,
                        y: 0
                    };
                gesture.target = el;
                
                if (mode == 'horizontal') {
                    el.style['-ms-touch-action'] = 'pan-x';
                    el.style['-ms-scroll-chaining'] = 'none';
                    el.style['touch-action'] = 'pan-x';
                    el.style['scroll-chaining'] = 'none';
                } else if (mode == 'vertical') {
                    el.style['-ms-touch-action'] = 'pan-y';
                    el.style['-ms-scroll-chaining'] = 'none';
                    el.style['touch-action'] = 'pan-y';
                    el.style['scroll-chaining'] = 'none';
                }
                
                var eventType = '';
                if (window.navigator.pointerEnabled) {
                    eventType = "pointerdown";
                } else if (window.navigator.msPointerEnabled) {
                    eventType = "MSPointerDown";
                }
                if(eventType){
                    el.addEventListener(eventType, function (evt) {
                        gesture.addPointer(evt.pointerId);
                    });
                }
                    
                var hOffset = 10,
                    vOffset = 10;  
                
                el.addEventListener("MSGestureStart", function(e){
                    start.x = e.offsetX;
                    start.y = e.offsetY;
                });

                el.addEventListener("MSGestureEnd", function(e){ 
                    var zoom = document.documentElement.clientWidth / window.innerWidth;
                    if (mode == 'horizontal') {
                        if (start.x-hOffset >= e.offsetX) { 
                            _this.next();
                        } else if (start.x+hOffset <= e.offsetX) {
                            _this.previous();
                        }
                    } else if (mode == 'vertical') {
                        if (start.y-vOffset >= e.offsetY) { 
                            _this.next();
                        } else if (start.y+vOffset <= e.offsetY) {
                            _this.previous();
                        }
                    }
                });
            }
        },
        
        __animateHorizontalTouch: function(direction){
            var target = {left: 0},
                active = this._active,
                i = null;
            if(direction == 'left'){
                i = active + 1;
                if (i === this.slideList.length) i = 0;
                target = {left: -this.slideDimension.w};
                this.__animateTouch(i, active, 'left', target, {left: 0});
            }else if(direction == 'right'){
                i = active - 1;
                if (i < 0) i = this.slideList.length - 1;
                target = {left: this.slideDimension.w};
                this.__animateTouch(i, active, 'left', target, {left: 0});
            }
        },
        
        __animateVerticalTouch: function(direction){
            var target = 0,
                active = this._active,
                i = null;

            if(direction == 'up'){
                i = active + 1;
                if (i === this.slideList.length) i = 0;
                target = {top: -this.slideDimension.h};
                this.__animateTouch(i, active, 'top', target, {top: 0});
            }else if(direction == 'down'){
                i = active - 1;
                if (i < 0) i = this.slideList.length - 1;
                target = {top: this.slideDimension.h};
                this.__animateTouch(i, active, 'top', target, {top: 0});
            }
        },
        
        __animateTouch: function(i, lastActive, prop, target, targetActive){
            
            if (!this.options.syncAnimations) {
                if (this._lastActive != i) this.slideList.eq(this._lastActive).trigger('ssanimatestop');
                this.slideList.eq(this._active).trigger('ssanimatestop');
            }

            var _this = this;

            this.pauseAutoPlay(true);

            this._animating = true;
            
            this.changeBullet(i);
            if (this.options.syncAnimations) _this._runningAnimations++;

            this._nextActive = i;
            
            this.changeBullet(i);
            
            $(this).trigger('mainanimationstart');

            this._active = i;
            this._lastActive = lastActive;
            
            this._runningAnimations++;
            
            if (this.options.flux[0]) this.flux.showImage(i);
            
            this.slideList.eq(lastActive).animate(target,{
                duration: 300,
                complete: function(){
                    $(this).css(prop, 0).css('left', '-1000%');
                    _this.$this.trigger('mainanimationoutend');
                    _this.mainanimationended();
                }
            }).trigger('ssanimatelayersout');
            this.slideList.eq(i).animate(targetActive,{
                duration: 300,
                complete: function(){
                    $(this).trigger('ssanimatelayersin');
                    _this.$this.trigger('mainanimationinend');
                    _this.mainanimationended();
                }
            });
        }
    });

})(njQuery, window);