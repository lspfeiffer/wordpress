/**
 * @preserve Flux Slider v1.4.4
 * http://www.joelambert.co.uk/flux
 *
 * Copyright 2011, Joe Lambert.
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

// Flux namespace
window.flux = {
    version: '1.4.4'
};

(function ($) {
    flux.slider = function (elem, opts) {
        // Setup the flux.browser singleton to perform feature detection
        flux.browser.init();

        if (!flux.browser.supportsTransitions) {
            if (window.console && window.console.error)
                console.error("Flux Slider requires a browser that supports CSS3 transitions");
        }

        var _this = this;

        this.element = $(elem);

        // Make a list of all available transitions
        this.transitions = [];
        for (var fx in flux.transitions)
            this.transitions.push(fx);

        this.options = $.extend({
            autoplay: false,
            transitions: this.transitions,
            delay: 4000,
            pagination: false,
            controls: false,
            captions: false,
            width: 600,
            height: 400,
            onTransitionEnd: null,
            currentImageIndex: 0,
            nextImageIndex: 1
        }, opts);

        // Set the height/width if given [EXPERIMENTAL!]
        this.height = this.options.height ? this.options.height : null;
        this.width = this.options.width ? this.options.width : null;

        var ua = navigator.userAgent.toLowerCase(),
            ios = (ua.match(/(ipad|iphone|ipod)/g) ? true : false),
            android = (ua.indexOf("android") > -1);
        // Filter out non compatible transitions
        var newTrans = [];
        $(this.options.transitions).each(function (index, tran) {
            var t = new flux.transitions[tran](this),
                compatible = true;

            if (t.options.requires3d && (!flux.browser.supports3d || ios || android))
                compatible = false;

            if (t.options.compatibilityCheck)
                compatible = t.options.compatibilityCheck();

            if (compatible)
                newTrans.push(tran);
        });

        if(newTrans.length == 0){
            newTrans[0] = ['dissolve'];
        }

        this.options.transitions = newTrans;

        this.images = new Array();
        this.imageLoadedCount = 0;
        this.currentImageIndex = this.options.currentImageIndex;
        this.nextImageIndex = this.options.nextImageIndex;
        this.playing = false;


        this.container = $('<div class="fluxslider"></div>').appendTo(this.element);

        this.surface = $('<div class="surface" style="position: relative"></div>').appendTo(this.container);

        // Listen for click events as we may want to follow a link
        this.container.bind('click', function (event) {
            if ($(event.target).hasClass('hasLink'))
                window.location = $(event.target).data('href');
        });

        this.imageContainer = $('<div class="images loading"></div>').css({
            'position': 'relative',
            /*'overflow': 'hidden',*/
            'min-height': '100px'
        }).appendTo(this.surface);

        this.image1img = $();
        this.image2img = $();

        // If the height/width is already set then resize the container
        if (this.width && this.height) {
            this.imageContainer.css({
                width: this.width + 'px',
                height: this.height + 'px'
            });
        }

        // Create the placeholders for the current and next image
        this.image1 = $('<div class="image1" style="height: 100%; width: 100%"></div>').appendTo(this.imageContainer);
        this.image2 = $('<div class="image2" style="height: 100%; width: 100%"></div>').appendTo(this.imageContainer);

        $(this.image1).add(this.image2).css({
            'position': 'absolute',
            'top': '0px',
            'left': '0px'
        });

        this.els = $([]);
        // Get a list of the images to use
        this.element.find('img, a img').each(function (index, found_img) {
            var imgClone = found_img.cloneNode(false),
                link = $(found_img).parent();

            // If this img is directly inside a link then save the link for later use
            if (link.is('a'))
                $(imgClone).data('href', link.attr('href'));

            _this.images.push(imgClone);

            _this.els.push(found_img);
        });

        // Load the images afterwards as IE seems to load images synchronously
        for (var i = 0; i < this.images.length; i++) {
            var image = new Image();
            image.onload = function () {
                _this.imageLoadedCount++;

                _this.width = _this.width ? _this.width : this.width;
                _this.height = _this.height ? _this.height : this.height;

                if (_this.imageLoadedCount >= _this.images.length) {
                    _this.finishedLoading();
                    _this.setupImages();
                }
            };

            // Load the image to ensure its cached by the browser
            image.src = this.images[i].src;
        }

        // Catch when a transition has finished
        this.element.bind('fluxTransitionEnd', function (event, data) {
            // If the slider is currently playing then set the timeout for the next transition
            // if(_this.isPlaying())
            // 	_this.start();

            // Are we using a callback instead of events for notifying about transition ends?
            if (_this.options.onTransitionEnd) {
                event.preventDefault();
                _this.options.onTransitionEnd(data);
            }
        });

        // Should we auto start the slider?
        if (this.options.autoplay)
            this.start();

        // Handle swipes
        this.element.bind('swipeLeft',function (event) {
            _this.next(null, {direction: 'left'});
        }).bind('swipeRight', function (event) {
                _this.prev(null, {direction: 'right'});
            });

        // Under FF7 autoplay breaks when the current tab loses focus
        setTimeout(function () {
            $(window).focus(function () {
                if (_this.isPlaying())
                    _this.next();
            });
        }, 100);
    };

    flux.slider.prototype = {
        constructor: flux.slider,
        playing: false,
        start: function () {
            var _this = this;
            this.playing = true;
            this.interval = setInterval(function () {
                _this.transition();
            }, this.options.delay);
        },
        stop: function () {
            this.playing = false;
            clearInterval(this.interval);
            this.interval = null;
        },
        isPlaying: function () {
            return this.playing;
            //return this.interval != null;
        },
        next: function (trans, opts) {
            opts = opts || {};
            opts.direction = 'left';
            this.showImage(this.currentImageIndex + 1, trans, opts);
        },
        prev: function (trans, opts) {
            opts = opts || {};
            opts.direction = 'right';
            this.showImage(this.currentImageIndex - 1, trans, opts);
        },
        showImage: function (index, trans, opts) {
            this.setNextIndex(index);

            // Temporarily stop the transition interval
            //clearInterval(this.interval);
            //this.interval = null;

            this.setupImages();
            this.transition(trans, opts);
        },
        finishedLoading: function () {
            var _this = this;

            this.container.css({
                width: '100%',
                height: '100%'
            });

            this.imageContainer.removeClass('loading');

            // Should we setup a pagination view?
            if (this.options.pagination) {
                // TODO: Attach to touch events if appropriate
                this.pagination = $('<ul class="pagination"></ul>').css({
                    margin: '-50px 0 0 0',
                    padding: '0px',
                    position: 'relative',
                    zIndex: '10000',
                    'text-align': 'center',
                    border: 0
                });

                this.pagination.bind('click', function (event) {
                    event.preventDefault();
                    _this.showImage($(event.target).data('index'));
                });

                $(this.images).each(function (index, image) {
                    var li = $('<li data-index="' + index + '">' + (index + 1) + '</li>').css({
                        display: 'inline-block',
                        'margin-left': '0.5em',
                        'cursor': 'pointer'
                    }).appendTo(_this.pagination);

                    if (index == 0)
                        li.css('margin-left', 0).addClass('current');
                });

                this.container.append(this.pagination);
            }

            // Resize
            $(this.imageContainer).css({
                width: this.width + 'px',
                height: this.height + 'px'
            });

            $(this.image1).css({
                width: this.width + 'px',
                height: this.height + 'px'
            });

            $(this.image2).css({
                width: this.width + 'px',
                height: this.height + 'px'
            });

            this.container.css({
                width: this.width + 'px',
                height: this.height + (this.options.pagination ? this.pagination.height() : 0) + 'px'
            });

            // Should we add prev/next controls?
            if (this.options.controls) {
                var css = {
                    padding: '4px 10px 10px',
                    'font-size': '60px',
                    'font-family': 'arial, sans-serif',
                    'line-height': '1em',
                    'font-weight': 'bold',
                    color: '#FFF',
                    'text-decoration': 'none',
                    background: 'rgba(0,0,0,0.5)',
                    position: 'absolute',
                    'z-index': 2000
                };

                this.nextButton = $('<a href="#">»</a>').css(css).css3({
                    'border-radius': '4px'
                }).appendTo(this.surface).bind('click', function (event) {
                        event.preventDefault();
                        _this.next();
                    });

                this.prevButton = $('<a href="#">«</a>').css(css).css3({
                    'border-radius': '4px'
                }).appendTo(this.surface).bind('click', function (event) {
                        event.preventDefault();
                        _this.prev();
                    });

                var top = (this.height - this.nextButton.height()) / 2;
                this.nextButton.css({
                    top: top + 'px',
                    right: '10px'
                });

                this.prevButton.css({
                    top: top + 'px',
                    left: '10px'
                });
            }

            // Should we use captions?
            if (this.options.captions) {
                this.captionBar = $('<div class="caption"></div>').css({
                    background: 'rgba(0,0,0,0.6)',
                    color: '#FFF',
                    'font-size': '16px',
                    'font-family': 'helvetica, arial, sans-serif',
                    'text-decoration': 'none',
                    'font-weight': 'bold',
                    padding: '1.5em 1em',
                    opacity: 0,
                    position: 'absolute',
                    'z-index': 110,
                    width: '100%',
                    bottom: 0
                }).css3({
                        'transition-property': 'opacity',
                        'transition-duration': '800ms',
                        'box-sizing': 'border-box'
                    }).prependTo(this.surface);
            }

            this.updateCaption();
            this.els.remove();
        },
        changeSize: function (w, h) {
            this.width = w;
            this.height = h;
            this.options.width = w;
            this.options.height = h;
            this.imageContainer.width(w).height(h);
            this.image1img.width(w);
            this.image2img.width(w);
            this.image1.width(w).height(h);
            this.image2.width(w).height(h);
        },
        setupImages: function () {
            var img1 = this.getImage(this.currentImageIndex),
                css1 = {
                    //'background-image': 'url("'+img1.src+'")',
                    'z-index': 101,
                    'cursor': 'auto'
                };
            if(typeof img1 == 'undefined') return false;

            // Does this image have an associated link?
            if ($(img1).data('href')) {
                css1.cursor = 'pointer';
                this.image1.addClass('hasLink');
                this.image1.data('href', $(img1).data('href'));
            }
            else {
                this.image1.removeClass('hasLink');
                this.image1.data('href', null);
            }

            this.image1.css(css1).children().remove();

            this.image1img = $('<img src="' + img1.src + '" />').css({
                width: this.width + 'px'
            });

            this.image1.append(this.image1img);

            this.image2.children().remove();
            this.image2img = $('<img src="' + this.getImage(this.nextImageIndex).src + '" />').css({
                width: this.width + 'px'
            });
            this.image2.append(this.image2img);
            this.image2.css({
                //'background-image': 'url("'+this.getImage(this.nextImageIndex).src+'")',
                'z-index': 100
            }).show();

            if (this.options.pagination && this.pagination) {
                this.pagination.find('li.current').removeClass('current');
                $(this.pagination.find('li')[this.currentImageIndex]).addClass('current');
            }
        },
        transition: function (transition, opts) {
            // Allow a transition to be picked from ALL available transitions (not just the reduced set)
            if (transition == undefined || !flux.transitions[transition]) {
                // Pick a transition at random from the (possibly reduced set of) transitions
                var index = Math.floor(Math.random() * (this.options.transitions.length));
                transition = this.options.transitions[index];
            }

            var tran = null;

            try {
                tran = new flux.transitions[transition](this, $.extend(this.options[transition] ? this.options[transition] : {}, opts));
            }
            catch (e) {
                // If an invalid transition has been provided then use the fallback (default is to just switch the image)
                tran = new flux.transition(this, {fallback: true});
            }

            tran.run();

            this.currentImageIndex = this.nextImageIndex;
            this.setNextIndex(this.currentImageIndex + 1);
            this.updateCaption();
        },
        updateCaption: function () {
            var str = $(this.getImage(this.currentImageIndex)).attr('title');
            if (this.options.captions && this.captionBar) {
                if (str !== "")
                    this.captionBar.html(str);

                this.captionBar.css('opacity', str === "" ? 0 : 1);
            }
        },
        getImage: function (index) {
            index = index % this.images.length;

            return this.images[index];
        },
        setNextIndex: function (nextIndex) {
            if (nextIndex == undefined)
                nextIndex = this.currentImageIndex + 1;

            this.nextImageIndex = nextIndex;

            if (this.nextImageIndex > this.images.length - 1)
                this.nextImageIndex = 0;

            if (this.nextImageIndex < 0)
                this.nextImageIndex = this.images.length - 1;
        },
        increment: function () {
            this.currentImageIndex++;
            if (this.currentImageIndex > this.images.length - 1)
                this.currentImageIndex = 0;
        }
    };
})(njQuery);

/**
 * Helper object to determine support for various CSS3 functions
 * @author Joe Lambert
 */

(function ($) {
    flux.browser = {
        init: function () {
            // Have we already been initialised?
            if (flux.browser.supportsTransitions !== undefined)
                return;

            var div = document.createElement('div'),
                prefixes = ['-webkit', '-moz', '-o', '-ms'],
                domPrefixes = ['Webkit', 'Moz', 'O', 'Ms'];

            // Does the current browser support CSS Transitions?
            if (window.nModernizr && nModernizr.csstransitions !== undefined)
                flux.browser.supportsTransitions = nModernizr.csstransitions;
            else {
                flux.browser.supportsTransitions = this.supportsCSSProperty('Transition');
            }

            // Does the current browser support 3D CSS Transforms?
            if (window.nModernizr && nModernizr.csstransforms3d !== undefined){
                flux.browser.supports3d = nModernizr.csstransforms3d;
                flux.browser.supportspreserve3d = nModernizr.transformstylepreserve3d;
                if(!flux.browser.supportspreserve3d) flux.browser.supports3d = false;
            }else {
                // Custom detection when Modernizr isn't available
                flux.browser.supports3d = this.supportsCSSProperty("Perspective");

                if (flux.browser.supports3d && 'webkitPerspective' in $('body').get(0).style) {
                    // Double check with a media query (similar to how Modernizr does this)
                    var div3D = $('<div id="csstransform3d"></div>');
                    var mq = $('<style media="(transform-3d), (' + prefixes.join('-transform-3d),(') + '-transform-3d)">div#csstransform3d { position: absolute; left: 9px }</style>');

                    $('body').append(div3D);
                    $('head').append(mq);

                    flux.browser.supports3d = div3D.get(0).offsetLeft == 9;

                    div3D.remove();
                    mq.remove();
                }
            }

        },
        supportsCSSProperty: function (prop) {
            var div = document.createElement('div'),
                prefixes = ['-webkit', '-moz', '-o', '-ms'],
                domPrefixes = ['Webkit', 'Moz', 'O', 'Ms'];

            var support = false;
            for (var i = 0; i < domPrefixes.length; i++) {
                if (domPrefixes[i] + prop in div.style)
                    support = support || true;
            }

            return support;
        },
        translate: function (x, y, z) {
            x = (x != undefined) ? x : 0;
            y = (y != undefined) ? y : 0;
            z = (z != undefined) ? z : 0;

            return 'translate' + (flux.browser.supports3d ? '3d(' : '(') + x + 'px,' + y + (flux.browser.supports3d ? 'px,' + z + 'px)' : 'px)');
        },

        rotateX: function (deg) {
            return flux.browser.rotate('x', deg);
        },

        rotateY: function (deg) {
            return flux.browser.rotate('y', deg);
        },

        rotateZ: function (deg) {
            return flux.browser.rotate('z', deg);
        },

        rotate: function (axis, deg) {
            if (!axis in {'x': '', 'y': '', 'z': ''})
                axis = 'z';

            deg = (deg != undefined) ? deg : 0;

            if (flux.browser.supports3d)
                return 'rotate3d(' + (axis == 'x' ? '1' : '0') + ', ' + (axis == 'y' ? '1' : '0') + ', ' + (axis == 'z' ? '1' : '0') + ', ' + deg + 'deg)';
            else {
                if (axis == 'z')
                    return 'rotate(' + deg + 'deg)';
                else
                    return '';
            }
        }
    };

    $(function () {
        // To continue to work with legacy code, ensure that flux.browser is initialised on document ready at the latest
        flux.browser.init();
    });
})(njQuery);

(function ($) {
    /**
     * Helper function for cross-browser CSS3 support, prepends all possible prefixes to all properties passed in
     * @param {Object} props Ker/value pairs of CSS3 properties
     */
    $.fn.css3 = function (props) {
        var css = {};
        var prefixes = ['webkit', 'moz', 'ms', 'o'];

        for (var prop in props) {
            // Add the vendor specific versions
            for (var i = 0; i < prefixes.length; i++)
                css['-' + prefixes[i] + '-' + prop] = props[prop];

            // Add the actual version
            css[prop] = props[prop];
        }

        this.css(css);
        return this;
    };

    /**
     * Helper function to bind to the correct transition end event
     * @param {function} callback The function to call when the event fires
     */
    $.fn.transitionEnd = function (callback) {
        var _this = this;

        var events = ['webkitTransitionEnd', 'transitionend', 'oTransitionEnd'];

        for (var i = 0; i < events.length; i++) {
            this.bind(events[i], function (event) {
                // Automatically stop listening for the event
                for (var j = 0; j < events.length; j++)
                    $(this).unbind(events[j]);

                // Perform the callback function
                if (callback)
                    callback.call(this, event);
            });
        }

        return this;
    };

    flux.transition = function (fluxslider, opts) {
        this.options = $.extend({
            requires3d: false,
            after: function () {
                // Default callback for after the transition has completed
            }
        }, opts);

        this.slider = fluxslider;

        // We need to ensure transitions degrade gracefully if the transition is unsupported or not loaded
        if ((this.options.requires3d && !flux.browser.supports3d) || !flux.browser.supportsTransitions || this.options.fallback === true) {
            var _this = this;

            this.options.after = undefined;

            this.options.setup = function () {
                //console.error("Fallback setup()");
                _this.fallbackSetup();
            };

            this.options.execute = function () {
                //console.error("Fallback execute()");
                _this.fallbackExecute();
            };
        }
    };

    flux.transition.prototype = {
        constructor: flux.transition,
        hasFinished: false, // This is a lock to ensure that the fluxTransitionEnd event is only fired once per tansition
        run: function () {
            var _this = this;

            // do something
            if (this.options.setup !== undefined)
                this.options.setup.call(this);

            // Remove the background image from the top image
            this.slider.image1.css({
                'background-image': 'none'
            });

            this.slider.imageContainer.css('overflow', this.options.requires3d ? 'visible' : 'hidden');

            // For some of the 3D effects using Zepto we need to delay the transitions for some reason
            setTimeout(function () {
                if (_this.options.execute !== undefined)
                    _this.options.execute.call(_this);
            }, 5);
        },
        finished: function () {
            if (this.hasFinished)
                return;

            this.hasFinished = true;

            if (this.options.after)
                this.options.after.call(this);

            this.slider.imageContainer.css('overflow', 'hidden');

            this.slider.setupImages();

            // Trigger an event to signal the end of a transition
            this.slider.element.trigger('fluxTransitionEnd', {
                currentImage: this.slider.getImage(this.slider.currentImageIndex)
            });
        },
        fallbackSetup: function () {

        },
        fallbackExecute: function () {
            this.finished();
        }
    };

    flux.transitions = {};

    // Flux grid transition

    flux.transition_grid = function (fluxslider, opts) {
        return new flux.transition(fluxslider, $.extend({
            columns: 6,
            rows: 6,
            forceSquare: false,
            setup: function () {
                var imgWidth = this.slider.image1.width(),
                    imgHeight = this.slider.image1.height();

                var colWidth = Math.floor(imgWidth / this.options.columns),
                    rowHeight = Math.floor(imgHeight / this.options.rows);

                if (this.options.forceSquare) {
                    rowHeight = colWidth;
                    this.options.rows = Math.floor(imgHeight / rowHeight);
                }

                // Work out how much space remains with the adjusted barWidth
                var colRemainder = imgWidth - (this.options.columns * colWidth),
                    colAddPerLoop = Math.ceil(colRemainder / this.options.columns),

                    rowRemainder = imgHeight - (this.options.rows * rowHeight),
                    rowAddPerLoop = Math.ceil(rowRemainder / this.options.rows),

                    delayBetweenBars = 150,
                    height = this.slider.image1.height(),
                    totalLeft = 0,
                    totalTop = 0,
                    fragment = document.createDocumentFragment();

                for (var i = 0; i < this.options.columns; i++) {
                    var thisColWidth = colWidth,
                        totalTop = 0;

                    if (colRemainder > 0) {
                        var add = colRemainder >= colAddPerLoop ? colAddPerLoop : colRemainder;
                        thisColWidth += add;
                        colRemainder -= add;
                    }

                    for (var j = 0; j < this.options.rows; j++) {
                        var thisRowHeight = rowHeight,
                            thisRowRemainder = rowRemainder;

                        if (thisRowRemainder > 0) {
                            var add = thisRowRemainder >= rowAddPerLoop ? rowAddPerLoop : thisRowRemainder;
                            thisRowHeight += add;
                            thisRowRemainder -= add;
                        }

                        var tile = $('<div class="tile tile-' + i + '-' + j + '"></div>').css({
                            width: thisColWidth + 'px',
                            height: thisRowHeight + 'px',
                            position: 'absolute',
                            top: totalTop + 'px',
                            left: totalLeft + 'px'
                        });

                        this.options.renderTile.call(this, tile, i, j, thisColWidth, thisRowHeight, totalLeft, totalTop);

                        fragment.appendChild(tile.get(0));

                        totalTop += thisRowHeight;
                    }

                    totalLeft += thisColWidth;
                }

                // Append the fragement to the surface
                this.slider.image1.get(0).appendChild(fragment);

                this.slider.image1.find('>img').remove();
                //this.slider.image2.find('>img').remove();

            },
            execute: function () {
                var _this = this,
                    height = this.slider.image1.height(),
                    bars = this.slider.image1.find('div.barcontainer');

                this.slider.image2.hide();

                // Get notified when the last transition has completed
                bars.last().transitionEnd(function (event) {
                    _this.slider.image2.show();

                    _this.finished();
                });
                bars.css3({
                    'transform': flux.browser.rotateX(-90) + ' ' + flux.browser.translate(0, height / 2, height / 2)
                });
            },
            renderTile: function (elem, colIndex, rowIndex, colWidth, rowHeight, leftOffset, topOffset) {

            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.bars = function (fluxslider, opts) {
        return new flux.transition_grid(fluxslider, $.extend({
            columns: 10,
            rows: 1,
            delayBetweenBars: 40,
            renderTile: function (elem, colIndex, rowIndex, colWidth, rowHeight, leftOffset, topOffset) {
                var $el = $(elem),
                    img = this.slider.image1.children().clone();
                img.css({
                    'position': 'absolute',
                    'top': 0,
                    'left': -leftOffset
                });
                $el.css('overflow', 'hidden').append(img)
                    .css3({
                        'transition-duration': '400ms',
                        'transition-timing-function': 'ease-in',
                        'transition-property': 'all',
                        'transition-delay': (colIndex * this.options.delayBetweenBars) + 'ms'
                    });
            },
            execute: function () {
                var _this = this;


                var height = this.slider.image1.height();

                var bars = this.slider.image1.find('div.tile');

                // Get notified when the last transition has completed
                $(bars[bars.length - 1]).transitionEnd(function () {
                    _this.finished();
                });

                setTimeout(function () {
                    bars.css({
                        'opacity': '0.5'
                    }).css3({
                            'transform': flux.browser.translate(0, height)
                        });
                }, 50);

            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.bars3d = function (fluxslider, opts) {
        return new flux.transition_grid(fluxslider, $.extend({
            requires3d: true,
            columns: 7,
            rows: 1,
            delayBetweenBars: 450,
            perspective: 1000,
            renderTile: function (elem, colIndex, rowIndex, colWidth, rowHeight, leftOffset, topOffset) {

                var img = this.slider.image1.children().clone();
                img.css({
                    'position': 'absolute',
                    'top': 0,
                    'left': -leftOffset
                });

                var bar = $('<div class="bar-' + colIndex + '"></div>').append(img).css({
                        width: colWidth + 'px',
                        height: '100%',
                        position: 'absolute',
                        top: '0px',
                        left: '0px',
                        'z-index': 200,
                        overflow: 'hidden'

                        //'background-image': this.slider.image1.css('background-image'),
                        //'background-position': '-'+leftOffset+'px 0px',
                        //'background-repeat': 'no-repeat'
                    }).css3({
                            'backface-visibility': 'hidden'
                        }),

                    img2 = this.slider.image2.children().clone(),
                    bar2 = $(bar.get(0).cloneNode(false))/*.css({
                     'background-image': 'url('+img2[0].src+')'
                     })*/.css3({
                            'transform': flux.browser.rotateX(90) + ' ' + flux.browser.translate(0, -rowHeight / 2, rowHeight / 2)
                        }),
                    left = $('<div class="side bar-' + colIndex + '"></div>').css({
                        width: rowHeight + 'px',
                        height: rowHeight + 'px',
                        position: 'absolute',
                        top: '0px',
                        left: '0px',
                        background: '#222',
                        'z-index': 190/*,
                         overflow: 'hidden'*/
                    }).css3({
                            'transform': 'rotateY(-90deg) ' + flux.browser.translate(-rowHeight / 2, 0, rowHeight / 2),
                            'backface-visibility': 'hidden',
                            'transform-style': 'preserve-3d'
                        }),

                    right = $(left.get(0).cloneNode(false)).css3({
                        'transform': flux.browser.rotateY(90) + ' ' + flux.browser.translate(rowHeight / 2, 0, colWidth - rowHeight / 2)
                    });
                $(elem).css({
                    width: colWidth + 'px',
                    height: '100%',
                    position: 'absolute',
                    top: '0px',
                    left: leftOffset + 'px',
                    'z-index': colIndex > this.options.columns / 2 ? 1000 - colIndex : 1000 // Fix for Chrome to ensure that the z-index layering is correct!
                }).css3({
                        'transition-duration': '800ms',
                        'transition-timing-function': 'linear',
                        'transition-property': 'all',
                        'transition-delay': (colIndex * this.options.delayBetweenBars) + 'ms',
                        'transform-style': 'preserve-3d'
                    }).append(bar).append(bar2.append(img2.css({
                        'position': 'absolute',
                        'top': 0,
                        'left': -leftOffset
                    }))).append(right).append(left);
            },
            execute: function () {
                this.slider.image1.css3({
                    'perspective': this.options.perspective,
                    'perspective-origin': '50% 50%'
                }).css({
                        '-moz-transform': 'perspective(' + this.options.perspective + 'px)',
                        '-moz-perspective': 'none',
                        '-moz-transform-style': 'preserve-3d'
                    });

                var _this = this,
                    height = this.slider.image1.height(),
                    bars = this.slider.image1.find('div.tile');

                this.slider.image2.hide();

                // Get notified when the last transition has completed
                bars.last().transitionEnd(function (event) {
                    _this.slider.image1.css3({
                        'transform-style': 'flat'
                    });

                    _this.slider.image2.show();

                    _this.finished();
                });

                setTimeout(function () {
                    bars.css3({
                        'transform': flux.browser.rotateX(-90) + ' ' + flux.browser.translate(0, height / 2, height / 2)
                    });
                }, 50);
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.blinds = function (fluxslider, opts) {
        return new flux.transitions.bars(fluxslider, $.extend({
            execute: function () {
                var _this = this;

                var height = this.slider.image1.height();

                var bars = this.slider.image1.find('div.tile');

                // Get notified when the last transition has completed
                $(bars[bars.length - 1]).transitionEnd(function () {
                    _this.finished();
                });

                setTimeout(function () {
                    bars.css({
                        'opacity': '0.5'
                    }).css3({
                            'transform': 'scalex(0)' // 0.0001
                        });
                }, 50);
            }
        }, opts));
    }
})(njQuery);

(function ($) {
    flux.transitions.blinds3d = function (fluxslider, opts) {
        return new flux.transitions.tiles3d(fluxslider, $.extend({
            forceSquare: false,
            rows: 1,
            columns: 6
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.zip = function (fluxslider, opts) {
        return new flux.transitions.bars(fluxslider, $.extend({
            execute: function () {
                var _this = this;

                var height = this.slider.image1.height();

                var bars = this.slider.image1.find('div.tile');

                // Get notified when the last transition has completed
                $(bars[bars.length - 1]).transitionEnd(function () {
                    _this.finished();
                });

                setTimeout(function () {
                    bars.each(function (index, bar) {
                        $(bar).css({
                            'opacity': '0.3'
                        }).css3({
                                'transform': flux.browser.translate(0, (index % 2 ? '-' + (2 * height) : height))
                            });
                    });
                }, 20);
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.blocks = function (fluxslider, opts) {
        return new flux.transition_grid(fluxslider, $.extend({
            forceSquare: true,
            delayBetweenBars: 100,
            renderTile: function (elem, colIndex, rowIndex, colWidth, rowHeight, leftOffset, topOffset) {
                var delay = Math.floor(Math.random() * 10 * this.options.delayBetweenBars);
                var img = this.slider.image1.children().clone();
                img.css({
                    'position': 'absolute',
                    'top': -topOffset,
                    'left': -leftOffset
                });
                $(elem).append(img).css({
                    /*'background-image': this.slider.image1.css('background-image'),
                     'background-position': '-'+leftOffset+'px -'+topOffset+'px'*/
                    overflow: 'hidden'
                }).css3({
                        'transition-duration': '350ms',
                        'transition-timing-function': 'ease-in',
                        'transition-property': 'all',
                        'transition-delay': delay + 'ms'
                    });

                // Keep track of the last elem to fire
                if (this.maxDelay === undefined)
                    this.maxDelay = 0;

                if (delay > this.maxDelay) {
                    this.maxDelay = delay;
                    this.maxDelayTile = elem;
                }
            },
            execute: function () {
                var _this = this;

                var blocks = this.slider.image1.find('div.tile');

                // Get notified when the last transition has completed
                this.maxDelayTile.transitionEnd(function () {
                    _this.finished();
                });

                setTimeout(function () {
                    blocks.each(function (index, block) {
                        $(block).css({
                            'opacity': '0'
                        }).css3({
                                'transform': 'scale(0.8)'
                            });
                    });
                }, 50);
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.blocks2 = function (fluxslider, opts) {
        return new flux.transition_grid(fluxslider, $.extend({
            cols: 12,
            forceSquare: true,
            delayBetweenDiagnols: 150,
            renderTile: function (elem, colIndex, rowIndex, colWidth, rowHeight, leftOffset, topOffset) {
                var delay = Math.floor(Math.random() * 10 * this.options.delayBetweenBars);
                var img = this.slider.image1.children().clone();
                img.css({
                    'position': 'absolute',
                    'top': -topOffset,
                    'left': -leftOffset
                });

                $(elem).append(img).css({
                    /*'background-image': this.slider.image1.css('background-image'),
                     'background-position': '-'+leftOffset+'px -'+topOffset+'px'*/
                    overflow: 'hidden'
                }).css3({
                        'transition-duration': '350ms',
                        'transition-timing-function': 'ease-in',
                        'transition-property': 'all',
                        'transition-delay': (colIndex + rowIndex) * this.options.delayBetweenDiagnols + 'ms',
                        'backface-visibility': 'hidden' // trigger hardware acceleration
                    });
            },
            execute: function () {
                var _this = this;

                var blocks = this.slider.image1.find('div.tile');

                // Get notified when the last transition has completed
                blocks.last().transitionEnd(function () {
                    _this.finished();
                });

                setTimeout(function () {
                    blocks.each(function (index, block) {
                        $(block).css({
                            'opacity': '0'
                        }).css3({
                                'transform': 'scale(0.8)'
                            });
                    });
                }, 50);
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.concentric = function (fluxslider, opts) {
        return new flux.transition(fluxslider, $.extend({
            blockSize: 60,
            delay: 150,
            alternate: false,
            setup: function () {
                var w = this.slider.image1.width(),
                    h = this.slider.image1.height(),
                    largestLength = Math.sqrt(w * w + h * h), // Largest length is the diagonal

                // How many blocks do we need?
                    blockCount = Math.ceil(((largestLength - this.options.blockSize) / 2) / this.options.blockSize) + 1, // 1 extra to account for the round border
                    fragment = document.createDocumentFragment();

                for (var i = 0; i < blockCount; i++) {
                    var thisBlockSize = (2 * i * this.options.blockSize) + this.options.blockSize;

                    var img = this.slider.image1.children().clone();
                    img.css({
                        'position': 'absolute',
                        'top': -(h - thisBlockSize) / 2,
                        'left': -(w - thisBlockSize) / 2
                    });

                    var block = $('<div></div>').attr('class', 'block block-' + i).append(img).css({
                        width: thisBlockSize + 'px',
                        height: thisBlockSize + 'px',
                        position: 'absolute',
                        top: ((h - thisBlockSize) / 2) + 'px',
                        left: ((w - thisBlockSize) / 2) + 'px',
                        overflow: 'hidden',

                        'z-index': 100 + (blockCount - i),
                        /*
                         'background-image': this.slider.image1.css('background-image'),
                         'background-position': 'center center'*/
                    }).css3({
                            'border-radius': thisBlockSize + 'px',
                            'transition-duration': '800ms',
                            'transition-timing-function': 'linear',
                            'transition-property': 'all',
                            'transition-delay': ((blockCount - i) * this.options.delay) + 'ms'
                        });

                    fragment.appendChild(block.get(0));
                }
                this.slider.image1.find('>img').remove();
                //this.slider.image1.append($(fragment));
                this.slider.image1.get(0).appendChild(fragment);
            },
            execute: function () {
                var _this = this;

                var blocks = this.slider.image1.find('div.block');

                // Get notified when the last transition has completed
                $(blocks[0]).transitionEnd(function () {
                    _this.finished();
                });

                setTimeout(function () {
                    blocks.each(function (index, block) {
                        $(block).css({
                            'opacity': '0'
                        }).css3({
                                'transform': flux.browser.rotateZ((!_this.options.alternate || index % 2 ? '' : '-') + '90')
                            });
                    });
                }, 50);
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.warp = function (fluxslider, opts) {
        return new flux.transitions.concentric(fluxslider, $.extend({
            delay: 30,
            alternate: true
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.cube = function (fluxslider, opts) {
        return new flux.transition(fluxslider, $.extend({
            requires3d: true,
            barWidth: 100,
            direction: 'left',
            perspective: 1000,
            setup: function () {
                var width = this.slider.image1.width();
                var height = this.slider.image1.height();

                // Setup the container to allow 3D perspective

                this.slider.image1.css3({
                    'perspective': this.options.perspective,
                    'perspective-origin': '50% 50%'
                });

                this.cubeContainer = $('<div class="cube"></div>').css({
                    width: width + 'px',
                    height: height + 'px',
                    position: 'relative'
                }).css3({
                        'transition-duration': '800ms',
                        'transition-timing-function': 'linear',
                        'transition-property': 'all',
                        'transform-style': 'preserve-3d'
                    });

                var css = {
                    height: '100%',
                    width: '100%',
                    position: 'absolute',
                    top: '0px',
                    left: '0px',
                    overflow: 'hidden'
                };

                var img = this.slider.image1.children().clone();
                img.css({
                    'position': 'absolute',
                    'top': 0,
                    'left': 0
                });
                var currentFace = $('<div class="face current"></div>').append(img).css(css).css3({
                    'backface-visibility': 'hidden'
                });

                this.cubeContainer.append(currentFace);
                this.slider.image1.find('>img').remove();

                var img2 = this.slider.image2.children().clone();
                img2.css({
                    'position': 'absolute',
                    'top': 0,
                    'left': 0
                });

                var nextFace = $('<div class="face next"></div>').append(img2).css(css).css3({
                    'transform': this.options.transitionStrings.call(this, this.options.direction, 'nextFace'),
                    'backface-visibility': 'hidden'
                });

                this.cubeContainer.append(nextFace);

                this.slider.image1.append(this.cubeContainer);
            },
            execute: function () {
                var _this = this;

                var width = this.slider.image1.width();
                var height = this.slider.image1.height();

                this.slider.image2.hide();
                this.cubeContainer.transitionEnd(function () {
                    _this.slider.image2.show();

                    _this.finished();
                });

                setTimeout(function () {
                    _this.cubeContainer.css3({
                        'transform': _this.options.transitionStrings.call(_this, _this.options.direction, 'container')
                    });
                }, 50);
            },
            transitionStrings: function (direction, elem) {
                var width = this.slider.image1.width();
                var height = this.slider.image1.height();

                // Define the various transforms that are required to perform various cube rotations
                var t = {
                    'up': {
                        'nextFace': flux.browser.rotateX(-90) + ' ' + flux.browser.translate(0, height / 2, height / 2),
                        'container': flux.browser.rotateX(90) + ' ' + flux.browser.translate(0, -height / 2, height / 2)
                    },
                    'down': {
                        'nextFace': flux.browser.rotateX(90) + ' ' + flux.browser.translate(0, -height / 2, height / 2),
                        'container': flux.browser.rotateX(-90) + ' ' + flux.browser.translate(0, height / 2, height / 2)
                    },
                    'left': {
                        'nextFace': flux.browser.rotateY(90) + ' ' + flux.browser.translate(width / 2, 0, width / 2),
                        'container': flux.browser.rotateY(-90) + ' ' + flux.browser.translate(-width / 2, 0, width / 2)
                    },
                    'right': {
                        'nextFace': flux.browser.rotateY(-90) + ' ' + flux.browser.translate(-width / 2, 0, width / 2),
                        'container': flux.browser.rotateY(90) + ' ' + flux.browser.translate(width / 2, 0, width / 2)
                    }
                };

                return (t[direction] && t[direction][elem]) ? t[direction][elem] : false;
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.tiles3d = function (fluxslider, opts) {
        return new flux.transition_grid(fluxslider, $.extend({
            requires3d: true,
            forceSquare: true,
            columns: 5,
            perspective: 600,
            delayBetweenBarsX: 200,
            delayBetweenBarsY: 150,
            renderTile: function (elem, colIndex, rowIndex, colWidth, rowHeight, leftOffset, topOffset) {

                var img = this.slider.image1.children().clone();
                img.css({
                    'position': 'absolute',
                    'top': -topOffset,
                    'left': -leftOffset
                }),
                    tile = $('<div></div>').css({
                        width: colWidth + 'px',
                        height: rowHeight + 'px',
                        position: 'absolute',
                        top: '0px',
                        left: '0px',
                        //'z-index': 200, // Removed to make compatible with FF10 (Chrome bug seems to have been fixed)
                        /*
                         'background-image': this.slider.image1.css('background-image'),
                         'background-position': '-'+leftOffset+'px -'+topOffset+'px',
                         'background-repeat': 'no-repeat',*/
                        overflow: 'hidden',
                        '-webkit-transform': 'translateZ(0.1px)'
                    }).css3({
                            'backface-visibility': 'hidden'
                        });

                var img2 = this.slider.image2.children().clone();
                img2.css({
                    'position': 'absolute',
                    'top': -topOffset,
                    'left': -leftOffset
                }),
                    tile2 = $(tile.get(0).cloneNode(false)).append(img2)/*.css({
                     'background-image': this.slider.image2.css('background-image')
                     //'z-index': 190 // Removed to make compatible with FF10 (Chrome bug seems to have been fixed)
                     })*/.css3({
                            'transform': flux.browser.rotateY(180),
                            'backface-visibility': 'hidden'
                        });

                tile.append(img);

                $(elem).css({
                    'z-index': (colIndex > this.options.columns / 2 ? 500 - colIndex : 500) + (rowIndex > this.options.rows / 2 ? 500 - rowIndex : 500) // Fix for Chrome to ensure that the z-index layering is correct!
                }).css3({
                        'transition-duration': '800ms',
                        'transition-timing-function': 'ease-out',
                        'transition-property': 'all',
                        'transition-delay': (colIndex * this.options.delayBetweenBarsX + rowIndex * this.options.delayBetweenBarsY) + 'ms',
                        'transform-style': 'preserve-3d'
                    }).append(tile).append(tile2);
            },
            execute: function () {
                this.slider.image1.css3({
                    'perspective': this.options.perspective,
                    'perspective-origin': '50% 50%'
                });

                var _this = this;

                var tiles = this.slider.image1.find('div.tile');

                this.slider.image2.hide();

                // Get notified when the last transition has completed
                tiles.last().transitionEnd(function (event) {
                    _this.slider.image2.show();

                    _this.finished();
                });

                setTimeout(function () {
                    tiles.css3({
                        'transform': flux.browser.rotateY(180)
                    });
                }, 50);
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.turn = function (fluxslider, opts) {
        return new flux.transition(fluxslider, $.extend({
            requires3d: true,
            perspective: 1300,
            direction: 'left',
            setup: function () {

                var h = fluxslider.height,
                    h2 = h / 2,
                    w = fluxslider.width,
                    w2 = w / 2;
                var img = this.slider.image1.children().clone();
                img.css({
                    'position': 'absolute',
                    'top': 0,
                    'left': (this.options.direction == 'left' ? -1 * (this.slider.image1.width() / 2) : 0)
                }),
                    img2 = this.slider.image2.children().clone();
                img2.css({
                    'position': 'absolute',
                    'top': 0,
                    'left': (this.options.direction == 'left' ? 0 : -1 * (this.slider.image1.width() / 2))
                }),
                    img12 = img.clone();
                var tab = $('<div class="tab"></div>').css({
                        width: w2,
                        height: h,
                        position: 'absolute',
                        top: '0px',
                        left: this.options.direction == 'left' ? w2 : '0',
                        'z-index': 101
                    }).css3({
                            'transform-style': 'preserve-3d',
                            'transition-duration': '1000ms',
                            'transition-timing-function': 'ease-out',
                            'transition-property': 'all',
                            'transform-origin': this.options.direction == 'left' ? 'left center' : 'right center'
                        }),

                    front = $('<div></div>').append(img).appendTo(tab).css({
                        /*'background-image': this.slider.image1.css('background-image'),
                         'background-position': (this.options.direction == 'left' ? '-'+(this.slider.image1.width()/2) : 0)+'px 0',*/
                        width: w,
                        height: h,
                        position: 'absolute',
                        top: '0',
                        left: '0',
                        '-webkit-transform': 'translateZ(0.1px)',
                        overflow: 'hidden'
                    }).css3({
                            'backface-visibility': 'hidden'
                        }),

                    back = $('<div></div>').append(img2).appendTo(tab).css({
                        /*'background-image': this.slider.image2.css('background-image'),
                         'background-position': (this.options.direction == 'left' ? 0 : '-'+(this.slider.image1.width()/2))+'px 0',*/
                        width: w / 2,
                        height: h,
                        position: 'absolute',
                        top: '0',
                        left: '0',
                        overflow: 'hidden'
                    }).css3({
                            transform: flux.browser.rotateY(180),
                            'backface-visibility': 'hidden'
                        }),

                    current = $('<div></div>').append(img12.css('left', (this.options.direction == 'left' ? 0 : '-' + (this.slider.image1.width() / 2)))).css({
                        position: 'absolute',
                        top: '0',
                        left: this.options.direction == 'left' ? '0' : w2,
                        width: w2,
                        height: h,
                        /*'background-image': this.slider.image1.css('background-image'),
                         'background-position': (this.options.direction == 'left' ? 0 : '-'+(this.slider.image1.width()/2))+'px 0',*/
                        'z-index': 100,
                        overflow: 'hidden'
                    }),

                    overlay = $('<div class="overlay"></div>').css({
                        position: 'absolute',
                        top: '0',
                        left: this.options.direction == 'left' ? w2 : '0',
                        width: w2,
                        height: h,
                        background: '#000',
                        opacity: 1,
                        overflow: 'hidden'
                    }).css3({
                            'transition-duration': '800ms',
                            'transition-timing-function': 'linear',
                            'transition-property': 'opacity'
                        }),

                    container = $('<div></div>').css({
                        width: w,
                        height: h,
                        position: 'absolute',
                        top: 0,
                        left: 0
                    }).css3({
                            'perspective': this.options.perspective,
                            'perspective-origin': '50% 50%'
                        }).append(tab).append(current).append(overlay);

                this.slider.image1.find('>img').remove();
                this.slider.image1.append(container);
            },
            execute: function () {
                var _this = this;

                this.slider.image1.find('div.tab').first().transitionEnd(function () {
                    _this.finished();
                });

                setTimeout(function () {
                    _this.slider.image1.find('div.tab').css3({
                        // 179 not 180 so that the tab rotates the correct way in Firefox
                        transform: flux.browser.rotateY(_this.options.direction == 'left' ? -179 : 179)
                    });
                    _this.slider.image1.find('div.overlay').css({
                        opacity: 0
                    });
                }, 50);
            }
        }, opts));
    };
})(njQuery);

(function ($) {
    flux.transitions.dissolve = function (fluxslider, opts) {
        return new flux.transition(fluxslider, $.extend({
            setup: function () {

                var img1 = this.slider.image1.children().clone(),
                    img = $('<div class="image"></div>').append(img1).css({
                    width: '100%',
                    height: '100%'
                }).css3({
                        'transition-duration': '600ms',
                        'transition-timing-function': 'ease-in',
                        'transition-property': 'opacity'
                    });

                this.slider.image1.append(img);
            },
            execute: function () {
                var _this = this,
                    img = this.slider.image1.find('div.image');

                // Get notified when the last transition has completed
                $(img).transitionEnd(function () {
                    _this.finished();
                });
                this.slider.image1.find('>img').remove();

                setTimeout(function () {
                    $(img).css({
                        'opacity': '0.0'
                    });
                }, 50);
            }
        }, opts));
    };
})(njQuery);
