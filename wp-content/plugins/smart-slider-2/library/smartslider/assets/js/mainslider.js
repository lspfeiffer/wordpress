;
(function ($, scope, undefined) {
    scope.ssTypeBase = NClass.extend({
        $this: null,
        $slider: null,
        slideList: null,
        _parent: null,
        _active: -1,
        _lastActive: -1,
        _animating: false,
        _runningAnimations: 0,
        lastAvailableWidth: 0,
        _ready: false,
        _currentmode: 'desktop',
        _device: 'desktop',
        init: function (parent, $el, options) {
            this.options = {
                syncAnimations: 1,
                translate3d: 1,
                mainlayer: true,
                playfirstlayer: 0,
                mainafterout: 1,
                inaftermain: 1,
                fadeonscroll: 0,
                autoplay: 0,
                autoplayConfig: {
                    duration: 5000,
                    counter: 0,
                    autoplayToSlide: 0,
                    stopautoplay: {
                        click: 1,
                        mouseenter: 1,
                        slideplaying: 1
                    },
                    resumeautoplay: {
                        mouseleave: 0,
                        slideplayed: 1
                    }
                },
                responsive: {
                    downscale: 0,
                    upscale: 0
                },
                controls: {
                    scroll: 0,
                    touch: 0,
                    keyboard: 0
                },
                blockrightclick: 0
            };
            this.slideDimension = {
                w: 0,
                h: 0
            };
            this.ssplay = false;
             
            var _this = this;
            this._parent = parent;

            $.extend(this.options, options);
            this.options.syncAnimations = this.options.mainafterout;

            this.$slider = $el;
            
            this.initVariables();
            
            if (this.options.translate3d && nModernizr && nModernizr.csstransforms3d) {
                this.$slider.css(nModernizr.prefixed('transform'), 'translate3d(0,0,0)');
                this.$slider.css(nModernizr.prefixed('perspective'), '1000');
            }
            
            if(this.options.blockrightclick && window.ssadmin !== 1){
                this.$slider.bind("contextmenu",function(e){
                    e.preventDefault();
                }); 
            }

            this.id = $el.attr('id');

            this.$this = $(this);

            this.slideList = $('.smart-slider-canvas', $el);
            
            this._afterInitCheck();
        },
        _afterInitCheck: function(){
            if(this.$slider.parent().is(':visible')){
                this.afterInit();
            }else{
                var _this = this;
                setTimeout(function(){
                    _this._afterInitCheck();
                }, 500);
            }
        },
        afterInit: function(){
            var _this = this;
            
            this.slideDimension.w = this.slideList.width();
            this.slideDimension.h = this.slideList.height();

            for (var i = 0; i < this.slideList.length; i++) {
                var slide = this.slideList[i];

                // syncronize layer animations with the slide changing
                slide.ssanimation = 0;
                this.slideList.eq(i).on('incrementanimation.ssanimation',function () {
                    this.ssanimation++;
                }).on('decrementanimation.ssanimation',function () {
                        this.ssanimation--;
                        if (this.ssanimation === 0) {
                            $(this).trigger('ssanimationsended');
                        }
                    }).on('noanimation.ssanimation', function () {
                        if (this.ssanimation === 0) {
                            $(this).trigger('ssanimationsended');
                        }
                    });

                // init layers
                slide.layers = new scope.ssLayers(this, slide, {
                    width: this.slideDimension.w,
                    height: this.slideDimension.h,
                    mainlayer: this.options.mainlayer
                });
                slide.layers.changeMode(this._currentmode);
            }
            
            this.slidebgList = $('.nextend-slide-bg', this.$slider);
            this.slidebgList.width(this.slideDimension.w);

            this._active = this.slideList.index($('.' + this._parent.slideActive, this.$slider));
            
            this.sizeInited();

            this._bullets = this.$slider.find('.nextend-bullet-container > .nextend-bullet');
            this._bullets.removeClass('active');
            this._bullets.eq(this._active).addClass('active');

            this._bar = this.$slider.find('.nextend-bar-slide');
            this._bar.removeClass('active');
            this._bar.eq(this._active).addClass('active');


            this._thumbnails = window[this.id + '-thumbnail'];
            this.changeThumbnail(this._active);


            if (window.ssadmin !== 1) {
            
                this._device = this.deviceType();
                
                _this._animating = true;
                
                $(this).on('load.first', function () {
                    $(this).off('load.first');
                    
                    this._ready = true;
                    
                    var show = function(){
                        _this.$slider.addClass('nextend-loaded');
                        $('#'+_this.id+'-placeholder').remove();
                        
                        _this.$slider.trigger('loaded');
                        
                        _this._animating = false;
                        if (_this.options.playfirstlayer) {
                            var canvas = $(_this.slideList[_this._active]);
                            canvas.on('ssanimationsended.first',function () {
                                $(this).off('ssanimationsended.first');
                            }).trigger('ssanimatelayersin');
                        }
                        _this.startAutoplay();
                    };
                    
                    if(_this.options.fadeonscroll){
                        var w = $(window),
                            t = _this.$slider.offset().top+_this.$slider.outerHeight(false)/2;
                        if(w.scrollTop()+w.height() > t){
                            show();
                        }else{
                            w.on('scroll.'+_this.id, function(){
                                if(w.scrollTop()+w.height() > t){
                                    w.off('scroll.'+_this.id);
                                    show();
                                }
                            });
                        }
                    }else{
                        show();
                    }
                });
                
                if (this.options.responsive.downscale || this.options.responsive.upscale) {
                    this.storeDefaults();
                    this.onResize();

                    $(window).on('resize', function () {
                        _this.onResize();
                    });
                    if(typeof artxJQuery != "undefined" && typeof artxJQuery.fn.on != "undefined"){
                        artxJQuery(window).on('responsive', function () {
                            _this.onResize();
                        });
                    }
                    if(typeof jQuery != "undefined" && typeof jQuery.fn.on != "undefined"){
                        jQuery(window).on('responsive', function () {
                            _this.onResize();
                        });
                    }
                    if(typeof jQuery.fn.fitText != 'undefined') jQuery(window).trigger('resize');
                } else {
                    this.storeDefaults();
                    this.onResize(1);
                    this.$slider.waitForImages(function () {
                        $(_this).trigger('load');
                    });
                }

                if (!this.options.playfirstlayer) {
                    this.slideList[this._active].layers.setOutStart();
                }

                this.initAutoplay();
                this.initWidgets();
                this.initScroll();
                this.initTouch();
                this.initKeyboard();
				        this.initEvents();

            } else {
                this.storeDefaults();
                $(this).trigger('load');
            }
        },
        ready: function(fn){
            if(this._ready){
                fn();
            }else{
                $(this).on('load.first', fn);
            }
        },
        refreshMode: function(){
            var basedon = this.options.responsive.basedon,
                screenwidth = window.innerWidth,
                mode = 'desktop';
                
            if(basedon == 'screen' || basedon == 'combined'){
                if(screenwidth < this.options.responsive.screenwidth.phone){
                    mode = 'phone';
                }else if(screenwidth < this.options.responsive.screenwidth.tablet){
                    mode = 'tablet';
                }
            }
            
            if(basedon == 'combined') basedon = 'device';
            
            if(basedon == 'device'){
                if(this._device == 'mobile'){
                    mode = 'phone';
                }else if(this._device == 'tablet'){
                    mode = 'tablet';
                }
            }
            if(this._currentmode != mode){
                this.$slider.addClass('nextend-'+mode).removeClass('nextend-'+this._currentmode);
                this._currentmode = mode;
                for (var i = 0; i < this.slideList.length; i++) {
                    var slide = this.slideList[i];
                    slide.layers.changeMode(mode);
                }
                return true;
            }
            return false;
        },
        sizeInited: function(){
        
        },
        storeDefaults: function () {
            this.variablesRefreshed();
        },
        onResize: function () {
            var _this = this;
            this.$slider.waitForImages(function () {
                $(_this).trigger('load');
            });
            //this.variablesRefreshed();
        },
        initVariables: function(){
            this.variables = {};
            this.variableEls = {
                top: this.$slider.find('[data-sstop]'),
                right: this.$slider.find('[data-ssright]'),
                bottom: this.$slider.find('[data-ssbottom]'),
                left: this.$slider.find('[data-ssleft]'),
                width: this.$slider.find('[data-sswidth]'),
                height: this.$slider.find('[data-ssheight]')
            };
            
            this.widgets = {
                previous: this.$slider.find('.nextend-arrow-previous'),
                next: this.$slider.find('.nextend-arrow-next'),
                bullet: this.$slider.find('.nextend-widget-bullet'),
                autoplay: this.$slider.find('.nextend-autoplay-button'),
                indicator: this.$slider.find('.nextend-indicator'),
                bar: this.$slider.find('.nextend-bar'),
                thumbnail: this.$slider.find('.nextend-thumbnail-container'),
                shadow: this.$slider.find('.nextend-shadow'),
                html: this.$slider.find('.nextend-widget-html')
            };
        },
        variablesRefreshed: function(){
            for (var key in this.widgets) {
                var el = this.widgets[key],
                    visible = el.is(":visible");
                this.variables[key+'width'] = visible ? el.outerWidth(false) : 0;
                this.variables[key+'height'] = visible ? el.outerHeight(false) : 0;
          	}
            
            for (var key in this.variables) {
                eval("var " + key + " = " + this.variables[key] + "");
          	}
            
            
            for (var k in this.variableEls) {
                for(var i = 0; i < this.variableEls[k].length;i++){
                    var el = this.variableEls[k].eq(i);
                    try{
                        el.css(k, eval(el.data('ss'+k))+'px');
                    }catch(e){
                        alert('Error in widget(#'+el.attr('id')+') position variable: '+e.message);
                    }
                }
            }
        },
        initWidgets: function () {
            var timeout = null,
                block = false,
                widgets = this.$slider.find('.nextend-widget-hover');
            if (widgets.length > 0) {
                this.$slider.on('mouseenter touchstart', function (e) {
                    if(block) return;
                    var slider = $(this);
                    if (timeout) clearTimeout(timeout);
                    widgets.css('visibility', 'visible');
                    if(e.type == 'touchstart'){
                        block = true;
                        setTimeout(function () {
                            block = false;
                        }, 1000);
                    }else{
                        setTimeout(function () {
                            slider.addClass('nextend-widget-hover-show');
                        }, 50);
                    }
                }).on('mouseleave', function () {
                        var slide = this;
                        if (timeout) clearTimeout(timeout);
                        timeout = setTimeout(function () {
                            $(slide).removeClass('nextend-widget-hover-show');
                            timeout = setTimeout(function () {
                                widgets.css('visibility', 'hidden');
                            }, 400);
                        }, 500);
                    });
            }
        },
        initScroll: function () {
            if (this.options.controls.scroll == 0) return;
            var _this = this;
            this.$slider.on('mousewheel', function (e, delta, deltaX, deltaY) {
                if (delta < 0) {
                    _this.next();
                } else {
                    _this.previous();
                }
                e.preventDefault();
            });
        },
        initTouch: function () {
            if (this.options.controls.touch == '0') return;
            var _this = this;
            var mode = this.options.controls.touch;
            var delayBetween = 500,
                last = 0;
                
            if(typeof jQuery != 'undefined' && typeof jQuery.UIkit != 'undefined'){
                var el = this.$slider.find('> div').eq(0);
                if (mode == 'horizontal') {
                    el.on('swipeRight', function(){
                        _this.previous();
                    }).on('swipeLeft', function(){
                        _this.next();
                    });
                } else if (mode == 'vertical') {
                    el.on('swipeDown', function(){
                        _this.previous();
                    }).on('swipeUp', function(){
                        _this.next();
                    });
                }
                el.on('tap', function(e){
                        var target = e.target;
                        var prevent = false;
                        var a = null;
                        if(target.tagName == 'A') a = $(target);
                        else a = $(target).closest('a');
                        if(a.length){
                            window.open(a.attr('href'),a.attr('target'));
                            prevent = true;
                        }
                        
                        if(!prevent){
                            var act = _this.slideList.eq(_this._active).trigger('click');
                            if(typeof act.attr("onclick") != 'undefined') prevent = true;
                        }
                        if(prevent){
                            event.preventDefault();
                            event.stopPropagation();
                        }
                });
            }else{
                this.$slider.find('> div').eq(0).swipe({
                    tap: function(event, target) {
                        var prevent = false;
                        var a = null;
                        if(target.tagName == 'A') a = $(target);
                        else a = $(target).closest('a');
                        if(a.length){
                            window.open(a.attr('href'),a.attr('target'));
                            prevent = true;
                        }
                        
                        if(!prevent){
                            var act = _this.slideList.eq(_this._active).trigger('click');
                            if(typeof act.attr("onclick") != 'undefined') prevent = true;
                        }
                        if(prevent){
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    },
                    swipe: function (event, direction, distance, duration, fingerCount) {
                        var c = Date.now();
                        if(last < c - delayBetween){
                            if (mode == 'horizontal') {
                                if (direction == 'right') {
                                    _this.previous();
                                } else if (direction == 'left') {
                                    _this.next();
                                }
                            } else if (mode == 'vertical') {
                                if (direction == 'down') {
                                    _this.previous();
                                } else if (direction == 'up') {
                                    _this.next();
                                }
                            }
                            last = c;
                        }
                    },
                    fallbackToMouseEvents: false,
                    allowPageScroll: (mode == 'horizontal' ? 'vertical' : 'horizontal')
                });
            }
            
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
                
                el.addEventListener("MSGestureStart", function(e){
                    start.x = e.offsetX;
                    start.y = e.offsetY;
                }); 

                el.addEventListener("MSGestureEnd", function(e){ 
                    var zoom = document.documentElement.clientWidth / window.innerWidth;
                    
                    var hOffset = 10,
                        vOffset = 10;  
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
        initKeyboard: function () {
            if (this.options.controls.keyboard == '0') return;
            var _this = this;
            var delayBetween = 500,
                last = 0;
                
            $(document).keydown(function(e){
                var c = Date.now();
                if(last < c - delayBetween){
                    if (e.keyCode == 37) { 
                       _this.previous();
                    }else if (e.keyCode == 39) { 
                       _this.next();
                    }
                    last = c;
                }
            });
        },
    		initEvents: function(){
    			this.$slider.find("*[data-click]").each(function(){
    				var thisme = $(this);
    				if(thisme.data('click')!=""){
    					thisme.on("click", function(){eval(thisme.data('click'));});
    				}
    			});
    			this.$slider.find("*[data-enter]").each(function(){
    				var thisme = $(this);
    				if(thisme.data('enter')!=""){
    					thisme.on("mouseenter", function(){eval(thisme.data('enter'));});
    				}
    			});
    			this.$slider.find("*[data-leave]").each(function(){
    				var thisme = $(this);
    				if(thisme.data('leave')!=""){
    					thisme.on("mouseleave", function(){eval(thisme.data('leave'));});
    				}
    			});
    		},
        next: function (autoplay) {
            var i = this._active + 1;
            if (i === this.slideList.length)
                i = 0;
            return this.changeTo(i, false, autoplay);
        },
        previous: function (autoplay) {
            var i = this._active - 1;
            if (i < 0)
                i = this.slideList.length - 1;
            return this.changeTo(i, true, autoplay);
        },
        changeTo: function (i, reversed, autoplay) {
            if (window.ssadmin || i === this._active || this._animating)
                return false;
            if (!this.options.syncAnimations) {
                if (this._lastActive != i) this.slideList.eq(this._lastActive).trigger('ssanimatestop');
                this.slideList.eq(this._active).trigger('ssanimatestop');
            }
            
            this.ssplay = false;    

            var _this = this;

            this.pauseAutoPlay(true);

            this._animating = true;

            if (this.options.syncAnimations) _this._runningAnimations++;

            this._nextActive = i;

            this.changeBullet(i);

            $(this).trigger('mainanimationstart');

            var $currentactiveslide = this.slideList.eq(this._active),
                $nextactiveslide = this.slideList.eq(i),
                playin = function () {

                    if (_this.options.inaftermain) {

                        $nextactiveslide.trigger('ssanimatelayerssetinstart');

                        _this.$this.on('mainanimationinend.inaftermain', function () {
                            _this.$this.off('mainanimationinend.inaftermain');
                            $nextactiveslide.trigger('ssanimatelayersin');
                        });
                        _this._runningAnimations++;
                        _this.animateIn(i, reversed);
                    } else {
                        _this._runningAnimations++;
                        _this.animateIn(i, reversed);
                        $nextactiveslide.trigger('ssanimatelayersin');
                    }
                };


            if (this.options.mainafterout) {
                $currentactiveslide.on('ssanimationsended.ssinaftermain', function () {
                    $currentactiveslide.off('ssanimationsended.ssinaftermain');
                    _this._runningAnimations++;
                    _this.animateOut(_this._active, reversed);
                    playin();
                });

                if (this.options.syncAnimations) {
                    $currentactiveslide.trigger('ssanimatelayersout');
                }
            } else {
                this._runningAnimations++;
                this.animateOut(this._active, reversed);

                if (this.options.syncAnimations) {
                    $currentactiveslide.trigger('ssanimatelayersout');
                }

                playin();
            }

        },
        animateOut: function (i, reversed) {
            var _this = this;
            this._lastActive = i;
            var $slide = this.slideList.eq(i);

            var motion = ssAnimationManager.getAnimation('no', $slide);
            $slide.on('ssanimationsended.ssmainanimateout',function () {
                $slide.off('ssanimationsended.ssmainanimateout');
                _this.$this.trigger('mainanimationoutend');
                _this.mainanimationended();
            }).trigger('ssoutanimationstart');
            motion.animateOut();
        },
        animateIn: function (i, reversed) {
            var _this = this;
            this._active = i;
            var $slide = this.slideList.eq(i);
            var motion = ssAnimationManager.getAnimation('no', $slide);
            $slide.on('ssanimationsended.ssmainanimatein',function () {
                $slide.off('ssanimationsended.ssmainanimatein');
                _this.$this.trigger('mainanimationinend');
                _this.mainanimationended();
                _this.mainanimationended();
            }).trigger('ssinanimationstart');
            motion.animateIn();
        },
        mainanimationended: function () {
            this._runningAnimations--;
            if (this._runningAnimations === 0) {
                this.slideList.eq(this._lastActive).removeClass(this._parent.slideActive);
                this.slideList[this._lastActive].layers.setHiddenState();
                this.slideList.eq(this._active).addClass(this._parent.slideActive);
                this._animating = false;
                this.$this.trigger('mainanimationend');
                this.startAutoplay();
                if(this.options.autoplayConfig.resumeautoplay.slidechanged) this.reStartAutoPlay();
            } else if (this._runningAnimations < 0) {
                this._runningAnimations = 0;
            }
        },
        changeBullet: function (i) {
            this._bullets.removeClass('active');
            this._bullets.eq(i).addClass('active');
            this._bar.removeClass('active');
            this._bar.eq(i).addClass('active');

            this.changeThumbnail(i);
        },
        changeThumbnail: function (i) {
            if (this._thumbnails) this._thumbnails.change(i);
        },

        initAutoplay: function () {
            var _this = this;
            this.indicator = window[this.id + '-indicator'];
            if (!this.indicator) {
                this.indicator = {
                    hide: function () {
                    },
                    show: function () {
                    },
                    refresh: function (val) {
                    }
                };
            }
            this.indicator.reset = function () {
                _this.indicatorProgress = 0;
                this.refresh(0);
            }
            this.autoplayTimer = null;
            var autoplay = this.options.autoplayConfig;
            if (autoplay.stopautoplay.click) {
                this.$slider.find('> div').eq(0).on('click', function () {
                    _this.pauseAutoPlay();
                });
            }
            if (autoplay.stopautoplay.mouseenter) {
                this.$slider.find('> div').eq(0).on('mouseenter', function () {
                    _this.pauseAutoPlay();
                });
            }
            if (autoplay.stopautoplay.slideplaying) {
                this.$slider.on('ssplaystarted', function () {
                    _this.ssplay = true;
                    _this.pauseAutoPlay();
                });
            }
            if (autoplay.resumeautoplay.mouseleave) {
                this.$slider.on('mouseleave', function () {
                    if (!_this.autoplayTimer){
                        if(!_this.ssplay){
                            _this.reStartAutoPlay();
                        }
                    }
                });
            }
            this.$slider.on('ssplayended', function () {
                _this.ssplay = false;
            });
            if (autoplay.resumeautoplay.slideplayed) {
                this.$slider.on('ssplayended', function () {
                    if (!_this.autoplayTimer)
                        _this.reStartAutoPlay();
                });
            }

            if (!this.autoplaybutton) this.autoplaybutton = this.$slider.find('.nextend-autoplay-button');
            if (!this.indicatorEl) this.indicatorEl = $('<div></div>');

            if (this.options.autoplay) {
                this.startAutoplay = this.startAutoplayWorking;
                this.startAutoplay();
            } else {
                this.pauseAutoPlay();
            }

        },

        startAutoplay: function () {

        },

        startAutoplayWorking: function () {
            var _this = this,
                duration = this.options.autoplayConfig.duration;

            if (this.autoplayTimer) {
                clearTimeout(this.autoplayTimer);
                this.autoplayTimer = null;
            }

            if (this.indicator) {
                var shift = 0,
                    d = duration,
                    prevProgress = 0,
                    invPrevProgress = 1;
                if (this.indicatorEl.data('animating') && _this.indicatorProgress) {
                    d *= (1 - _this.indicatorProgress);
                    prevProgress = _this.indicatorProgress;
                    invPrevProgress = 1 - prevProgress;
                } else {
                    this.indicator.refresh(0);
                }
                this.indicatorEl.animate({
                    width: 1
                }, {
                    duration: d,
                    progress: function (e, i) {
                        var j = prevProgress + invPrevProgress * i;
                        _this.indicator.refresh(j * 100);
                        _this.indicatorProgress = j;
                    },
                    complete: function () {
                        _this.options.autoplayConfig.counter++;
                        _this.next(true);
                        _this.indicatorEl.data('animating', false);
                        _this.indicatorEl.stop(true);
                        _this.indicatorProgress = 0;
                        if(!_this.options.autoplayConfig.autoplayToSlide || _this.options.autoplayConfig.counter < _this.options.autoplayConfig.autoplayToSlide-1) _this.reStartAutoPlay();
                    }
                });
                this.indicatorEl.data('animating', true);
            } else {

                this.autoplayTimer = setTimeout(function () {
                    this.options.autoplayConfig.counter++;
                    _this.next(true);
                    _this.indicatorEl.stop(true);

                    _this.indicator.refresh(100);
                    if(!_this.options.autoplayConfig.autoplayToSlide || _this.options.autoplayConfig.counter < _this.options.autoplayConfig.autoplayToSlide-1) _this.reStartAutoPlay();
                }, duration);
            }
        },

        pauseAutoPlay: function (reset) {
            if (this.autoplayTimer) {
                clearTimeout(this.autoplayTimer);
                this.autoplayTimer = null;
            }
            this.autoplaybutton.addClass('paused');
            this.indicatorEl.stop(true);
            if (reset) {
                this.indicator.reset();
            }
            this.startAutoplay = function () {
            };
        },
        reStartAutoPlay: function () {
            this.autoplaybutton.removeClass('paused');
            this.startAutoplay = this.startAutoplayWorking;
            if (this._runningAnimations === 0) this.startAutoplay();
        },
        deviceType: function(){
            var ua = window.navigator ? window.navigator.userAgent : window.request ? window.request.headers['user-agent'] : 'No User-Agent Provided';
                    // smart tv
            return ua.match(/GoogleTV|SmartTV|Internet.TV|NetCast|NETTV|AppleTV|boxee|Kylo|Roku|DLNADOC|CE\-HTML/i) ? 'desktop'
                    // tv-based gaming console
                  : ua.match(/Xbox|PLAYSTATION.3|Wii/i) ? 'desktop'
                    // tablet
                  : ua.match(/iPad/i) || ua.match(/tablet/i) && !ua.match(/tablet pc/i) && !ua.match(/RX-34/i) || ua.match(/FOLIO/i) ? 'tablet'
                    // android tablet
                  : ua.match(/Linux/i) && ua.match(/Android/i) && !ua.match(/Fennec|mobi|HTC.Magic|HTCX06HT|Nexus.One|SC-02B|fone.945/i) ? 'tablet'
                    // Kindle or Kindle Fire
                  : ua.match(/Kindle/i) || ua.match(/Mac.OS/i) && ua.match(/Silk/i) ? 'tablet'
                    // pre Android 3.0 Tablet
                  : ua.match(/GT-P10|SC-01C|SHW-M180S|SGH-T849|SCH-I800|SHW-M180L|SPH-P100|SGH-I987|zt180|HTC(.Flyer|\_Flyer)|Sprint.ATP51|ViewPad7|pandigital(sprnova|nova)|Ideos.S7|Dell.Streak.7|Advent.Vega|A101IT|A70BHT|MID7015|Next2|nook/i) || ua.match(/MB511/i) && ua.match(/RUTEM/i) ? 'tablet'
                    // unique Mobile User Agent
                  : ua.match(/BOLT|Fennec|Iris|Maemo|Minimo|Mobi|mowser|NetFront|Novarra|Prism|RX-34|Skyfire|Tear|XV6875|XV6975|Google.Wireless.Transcoder/i) ? 'mobile'
                    // odd Opera User Agent - http://goo.gl/nK90K
                  : ua.match(/Opera/i) && ua.match(/Windows.NT.5/i) && ua.match(/HTC|Xda|Mini|Vario|SAMSUNG\-GT\-i8000|SAMSUNG\-SGH\-i9/i) ? 'mobile'
                    // Windows Desktop
                  : ua.match(/Windows.(NT|XP|ME|9)/) && !ua.match(/Phone/i) || ua.match(/Win(9|.9|NT)/i) ? 'desktop'
                    // Mac Desktop
                  : ua.match(/Macintosh|PowerPC/i) && !ua.match(/Silk/i) ? 'desktop'
                    // Linux Desktop
                  : ua.match(/Linux/i) && ua.match(/X11/i) ? 'desktop'
                    // Solaris, SunOS, BSD Desktop
                  : ua.match(/Solaris|SunOS|BSD/i) ? 'desktop'
                    // Desktop BOT/Crawler/Spider
                  : ua.match(/Bot|Crawler|Spider|Yahoo|ia_archiver|Covario-IDS|findlinks|DataparkSearch|larbin|Mediapartners-Google|NG-Search|Snappy|Teoma|Jeeves|TinEye/i) && !ua.match(/Mobile/i) ? 'desktop'
                  : 'desktop';
      }
    });

})(njQuery, window);

if (!Date.now) {
    Date.now = function now() {
        return new Date().getTime();
    };
}