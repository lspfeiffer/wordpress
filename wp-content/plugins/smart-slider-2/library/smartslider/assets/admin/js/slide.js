njQuery.fn.ssdata = function (key, value) {
    if (value === null) {
        this.removeAttr('data-' + key);
        return this;
    } else if (value === undefined) {
        return this.attr('data-' + key);
    } else {
        njQuery(this).data(key, value);
        this.attr('data-' + key, value);
        return this;
    }
};
njQuery.fireEvent = function (el, eventName) {
    var event;
    if (document.createEvent) {
        event = document.createEvent('HTMLEvents');
        event.initEvent(eventName, true, true);
    } else if (document.createEventObject) {// IE < 9
        event = document.createEventObject();
        event.eventType = eventName;
    }
    event.eventName = eventName;
    if (el.dispatchEvent) {
        el.dispatchEvent(event);
    } else if (el.fireEvent && htmlEvents['on' + eventName]) {// IE < 9
        el.fireEvent('on' + event.eventType, event);// can trigger only real event (e.g. 'click')
    } else if (el[eventName]) {
        el[eventName]();
    } else if (el['on' + eventName]) {
        el['on' + eventName]();
    }
};

window.ssadmin = 1;

;
(function ($, scope, undefined) {

    window.SmartSliderAdminSlide = function (id, active, hidden, layouturl) {
        scope.adminSlide = new scope.ssadminSlideClass(id, active, hidden, layouturl);
    };


    scope.ssadminSlideClass = NClass.extend({
        ss: null,
        outplayed: false,
        init: function (id, active, hidden, layouturl) {
            var $this = this;

            var ie = this.isIE();
            if(ie && ie < 10){
                alert(window.ss2lang.The_editor_was_tested_under_Internet_Explorer_10_Firefox_and_Chrome_Please_use_one_of_the_tested_browser);
            }
            
            window.nextendtime = $.now();
            window.nextendsave = false;

            this.hidden = $('#' + hidden);
            this.$slider = $('#' + id);
            
            this.$slide = this.$slider.find('.smart-slider-canvas').eq(active);
            this.editAndList();
            this.ssadminLayers = scope.ssadminLayers = new ssadminLayersClass(this.$slide, this, layouturl);
            
            this.initBG();

            $('#smartslider-form').submit(function () {
                if ($this.$slide[0].ssanimation === 0) {
                    $('.smartslider-slide-advanced-layers').remove();
                    $this.hidden.val(Base64.encode($this.ssadminLayers.getHTML()));
                    window.nextendsave = true;
                    return true;
                } else {
                    return false;
                }
            });

            this.initTopbar();
        },
        isIE: function () {
            var myNav = navigator.userAgent.toLowerCase();
            return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
        },
        initBG: function(){
            var $this = this,
                bgimage = this.$slider.find('.nextend-slide-bg'),
                canvas = this.$slider.find('.smart-slider-bg-colored');
            $('#slidebackground').on('change', function(){
                var s = this.value.split('|*|');
                
                if(s[1] == ''){
                    bgimage.css('display', 'none');
                }else{
                    bgimage.css('display', 'block');
                    bgimage.attr('src', $this.ssadminLayers.items.fillItemWithSample(s[1]));
                }
                if(s[0].substr(6,8) == '00'){
                    canvas.css('background', '');
                }else{
                    canvas.css('background', '#'+s[0].substr(0,6));
                    canvas.css('background', hex2rgba(s[0]));
                }
            }).trigger('change');;
        },
        initTopbar: function () {
            var $this = this;

            this.playing = 0;
            this.playbtn = $('.smartslider-toolbar-play').on('click', function () {
                $this.switchPlay();
            });

            this.$slide.on('ssanimationsended', function () {
                setTimeout(function () {
                    $this.playEnded();
                }, 300);
            });
        },
        getSS: function () {
            if (this.ss === null) {
                this.ss = this.$slider.data('smartslider').slider.mainslider;
            }
            return this.ss;
        },
        switchPlay: function () {
            var $this = this;
            if (!this.playing && this.$slide[0].ssanimation === 0) {
                this.playing = 1;
                slideconsole.set(window.ss2lang.Playing_in_animations_edit_and_save_disabled, 2, 0);
                this.playbtn.addClass('active');
                var layers = this.$slide[0].layers;
                this.getSS().refreshMode();
                layers/*.refresh()*/.setInStart().animateIn();
                setTimeout(function () {
                    $this.playEnded();
                }, 300);
            }
        },
        playOut: function () {
            var $this = this,
                layers = this.$slide[0].layers;
            this.outplayed = true;
            slideconsole.set(window.ss2lang.Playing_out_animations_edit_and_save_disabled, 2, 0);
            layers.animateOut();
            setTimeout(function () {
                $this.playEnded();
            }, 300);
        },
        playEnded: function () {
            if (this.$slide[0].ssanimation === 0 && this.playbtn.hasClass('active')) {
                if (this.outplayed === false) {
                    var $this = this;
                    slideconsole.set(window.ss2lang.In_animations_ended_edit_and_save_disabled, 2);
                    setTimeout(function () {
                        $this.playOut();
                    }, 2000);
                } else {
                    var layers = this.$slide[0].layers;
                    this.getSS().refreshMode();
                    layers/*.refresh()*/.resetOut().resetIn();
                    this.outplayed = false;
                    this.playbtn.removeClass('active');
                    slideconsole.set(window.ss2lang.Animations_ended_edit_and_save_enabled, 2);
                    this.playing = 0;
                }
            }
        },
        editAndList: function () {
            var $toolbox = $('#smartslider-slide-toolbox'),
                $list = $('.smartslider-toolbar-list'),
                $edit = $('.smartslider-toolbar-edit'),
                classes = ['smartslider-slide-toolbox-sliders-active', 'smartslider-slide-toolbox-slide-active'],
                extra = 0;
            
            if(typeof window.wp != 'undefined'){
                extra = 28;
            }
            $edit.on('click', function () {
                $toolbox.addClass(classes[1]).removeClass(classes[0]);
            });
            $list.on('click', function () {
                $toolbox.addClass(classes[0]).removeClass(classes[1]);
            });
            this.switchToEdit = function () {
                $toolbox.addClass(classes[1]).removeClass(classes[0]);
            }


            var maxOffset = parseInt($('.smartslider-slide-console').siblings('h3').offset().top),
                minOffset = parseInt($toolbox.offset().top)
            scrollFn = function () {
                var st = $(this).scrollTop()+extra;
                if (st < minOffset) {
                    $toolbox.css('marginTop', 0);
                } else if (st > maxOffset) {
                    $toolbox.css('marginTop', maxOffset - minOffset);
                } else {
                    $toolbox.css('marginTop', st - minOffset);
                }
                window.nextendsmartslidercolresize();
            };

            $(window).scroll(scrollFn);
            scrollFn();
        }
    });

})(njQuery, window);

function hex2rgba(hex) {
    var r = hexdec(hex.substr(0, 2));
    var g = hexdec(hex.substr(2, 2));
    var b = hexdec(hex.substr(4, 2));
    var a = (intval(hexdec(hex.substr(6, 2)))) / 255;
    a = a.toFixed(3);
    var color = r + "," + g + "," + b + "," + a;
    return 'RGBA(' + color + ')';
}

function hexdec(hex_string) {
    hex_string = (hex_string + '').replace(/[^a-f0-9]/gi, '');
    return parseInt(hex_string, 16);
}

function intval(mixed_var, base) {
    var tmp;
    var type = typeof(mixed_var);
    if (type === 'boolean') {
        return +mixed_var;
    } else if (type === 'string') {
        tmp = parseInt(mixed_var, base || 10);
        return (isNaN(tmp) || !isFinite(tmp)) ? 0 : tmp;
    } else if (type === 'number' && isFinite(mixed_var)) {
        return mixed_var | 0;
    } else {
        return 0;
    }
}