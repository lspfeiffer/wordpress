(function ($, scope, undefined) {

    scope.ssadminItemsClass = NClass.extend({
        active: false,
        init: function (layers) {
            var $this = this;
            this.layers = layers;
            this.slide = layers.slide;
            
            if(typeof window.samplegeneratordata !== 'undefined'){
                this.fillItemWithSample = this._fillItemWithSample;
            }

            this.activeItem = $({});
            this.activeItemType = '';

            this.dummyitem = $('<div />').ssdata('itemvalues', JSON.stringify({}));
            this.form = {};

            this.form.select = $('#itemitems_select').on('change', function () {
                $this.changeActiveItem();
            }).css('float', 'left');
            ndojo.disconnect(this.form.select[0].dojohandle);

            var deleteItem = $('<a href="#" class="smartslider-icon smartslider-icon-trash"></a>');
            var selectparent = this.form.select.parent();
            deleteItem.css({
                float: 'left',
                marginTop: '2px'
            });
            deleteItem.appendTo(selectparent);
            deleteItem.on('click', function (e) {
                e.preventDefault();
                var si = $this.form.select[0].selectedIndex;
                if (si) {
                    if (confirm(window.ss2lang.Are_you_sure_that_you_want_to_delete_the_item)) {
                        var item = $($this.form.select[0].options[si]).data('ssitem');
                        $this.deleteItem(item[0]);
                    }
                }else{
                    alert(window.ss2lang.Item_not_selected);
                }
            });

            var duplicateItem = $('<a href="#" class="smartslider-icon smartslider-icon-duplicate"></a>');
            duplicateItem.css({
                float: 'left',
                marginTop: '2px'
            });
            duplicateItem.appendTo(selectparent);
            duplicateItem.on('click', function (e) {
                e.preventDefault();
                var si = $this.form.select[0].selectedIndex;
                if (si) {
                    var $curr = $($this.form.select[0].options[si]).data('ssitem'),
                        $item = $curr.clone();
                    $item.find('[class^=ui-]').remove();
                    $item.removeClass('active');
                    $item.appendTo($curr.parent());
                    $this.makeItemRemovable($item[0]);
                    $this.makeItemMovable($item[0]);
                    $this.makeItemActivable($item[0]);
                    $this.setActiveItem($item[0]);
                }else{
                    alert(window.ss2lang.Item_not_selected);
                }
            });

            this.items = $('#draggableitems .smart-slider-item-container');
            this.items.qtip({
                content: {
                    text: window.ss2lang.Drag_the_item_and_drop_into_a_layer
                },
                show: {
                    event: 'mousedown'
                },
                position: {
                    my: "bottom left",
                    at: "top center"
                }
            });


            this.items.draggable({
                cursor: 'pointer',
                helper: 'clone',
                zIndex: 12,
                opacity: 0.5,
                connectToSortable: this.layers.layers.add(this.layers.dummyLayer),
                appendTo: $('#smartslider-form'),
                start: function () {
                    window.ssdrag = true;
                    $this.slide.addClass('smartslider-layer-border-mode');
                    slideconsole.set(window.ss2lang.Drop_the_item_into_a_layer, 2, 0);
                },
                stop: function () {
                    window.ssdrag = false;
                    $this.layers.leaveborder = false;
                    $this.slide.removeClass('smartslider-layer-border-mode');
                    slideconsole.set(window.ss2lang.Item_dropped_into_the_layer, 2);
                }
            }).on('mousedown', function () {
                    $this.layers.leaveborder = true;
                });

            this.views = this.layers.views;
            this.toolboxviews = this.layers.toolboxviews;

            this.views.eq(2).on('click', function () {
                $this.switchToItemTab();
            });
        },
        refreshSortableConnect: function () {
            this.items.draggable("option", "connectToSortable", this.layers.layers.add(this.layers.dummyLayer));
        },
        switchToItemTab: function () {
            this.views.removeClass('active');
            this.views.eq(2).addClass('active');
            this.toolboxviews.removeClass('active');
            this.toolboxviews.eq(2).addClass('active');
            this.toolboxviews.parent().css((window.nextendDir == 'rtl' ? 'marginRight' : 'marginLeft'), (window.nextendDir == 'rtl' ? '0' : '-200%'));
            this.layers.parent.switchToEdit();
            $('#smartslider-admin').removeClass('smartslider-layer-mode-active');
            $('#smartslider-admin').addClass('smartslider-item-mode-active');
            $(window).trigger('resize');
        },
        enableItemMode: function () {
            if (this.active === true)
                return;
            this.active = true;
            this.itemModeChanged();
        },
        disableItemMode: function () {
            if (this.active === false)
                return;
            this.active = false;
            this.itemModeChanged();
        },
        itemModeChanged: function () {
            var $this = this;
            if ($this.active) {
                this.layers.layers.nextendSortable('enable');
                $this.slide.addClass('smartslider-item-mode');
            } else {
                this.layers.layers.nextendSortable('disable');
                $this.slide.removeClass('smartslider-item-mode');
            }
        },
        initLayer: function (layer) {
            var $layer = $(layer),
                $this = this;
            $layer.find('.smart-slider-items').each(function () {
                $this.makeItemRemovable(this);
                $this.makeItemMovable(this);
                $this.makeItemActivable(this);
            });
        },
        updateItem: function (ui) {
            if (ui.item.parent()[0] === this.layers.dummyLayer[0]) {
                ui.item.remove();
                return;
            }
            var addeditem = ui.item.find('.smart-slider-items').css('display', '');
            if (addeditem.length > 0) { // item add from outside
                ui.item.replaceWith(addeditem);
                this.makeItemRemovable(addeditem);
                this.makeItemMovable(addeditem);
                this.makeItemActivable(addeditem);
                this.setActiveItem(addeditem);
            } else {
                var item = ui.item,
                    next = item.next('.smart-slider-items');
                if (next.length === 1) {
                    next.data('ssoption').before(ui.item.data('ssoption'));
                } else if(ui.item.parent().length){
                    ui.item.closest('.smart-slider-layer').data('ssoptgroup').append(ui.item.data('ssoption'));
                }else{
                    ui.item.remove();
                }
            }
        },
        deleteItem: function (item) {
            $(item).data('ssoption').remove();
            this.changeActiveItem();

            $(item).remove();
            slideconsole.set(window.ss2lang.Item_deleted, 2);
        },
        makeItemRemovable: function (item) {
            var $this = this,
                removeItem = $('<div class="ui-helper ui-removeitem-handle" style="z-index: 92;"></div>');
            $(item).append(removeItem);
            removeItem.on('click',function () {
                $this.deleteItem(item);
            }).on('mouseenter',function () {
                    slideconsole.set(window.ss2lang.Delete_item_click, 1, 0);
                }).on('mouseleave', function () {
                    slideconsole.set('', 1, 0);
                });
        },
        makeItemMovable: function (item) {
            var $this = this,
                moveItem = $('<div class="ui-helper ui-movableitem-handle" style="z-index: 92;"></div>');
            $(item).append(moveItem);
            moveItem.on('mouseenter',function () {
                slideconsole.set(window.ss2lang.Move_item_drag_and_drop_into_layers, 1, 0);
            }).on('mouseleave', function () {
                    slideconsole.set('', 1, 0);
                });
        },
        makeItemActivable: function (item) {
            var $this = this,
                $item = $(item),
                overlayItem = $('<div class="ui-helper ui-item-overlay" style="z-index: 89;"></div>');
            $item.append(overlayItem);
            overlayItem.on('mouseenter',function () {
                slideconsole.set(window.ss2lang.Select_item_click, 1, 0);
            }).on('mouseleave', function () {
                    slideconsole.set('', 1, 0);
                });

            $item.ssdata('item');

            var $option = $('<option>' + $item.ssdata('item') + '</option>');
            $item.data('ssoption', $option);
            $option.data('ssitem', $item);
            var next = $item.next('.smart-slider-items');
            if (next.length === 1 && next.data('ssoption')) {
                next.data('ssoption').before($option);
            } else {
                $item.parent().data('ssoptgroup').append($option);
            }

            $item.on('click',function () {
                $this.setActiveItem(item);
                slideconsole.set(window.ss2lang.Item_selected, 2);
            }).on('mousedown', function (e) {
                    $this.clicked = true;
                    setTimeout(function () {
                        $this.clicked = false;
                    }, 200);
                });
        },
        changeActiveItem: function () {
            var select = this.form.select[0],
                i = select.selectedIndex,
                item = $(select.options[i]).data('ssitem');
            if (item !== undefined) {
                item.trigger('mousedown').trigger('mouseup').trigger('click');
            } else {
                this.setActiveItem(this.dummyitem[0]);
            }
        },
        setActiveItem: function (item) {
            var $this = this,
                $item = $(item),
                type = $item.data('item'),
                values = JSON.parse($item.ssdata('itemvalues'));

            if (this.form[type] === undefined) {
                this.form[type] = {};
                this.form[type].form = $('#smartslider-slide-toolbox-item-type-' + type);
                this.form[type].template = this.form[type].form.data('itemtemplate');

                this.form[type].fields = this.form[type].form.find('[name^="item_' + type + '"]');
                this.form[type].fields.on('change keydown', function () {
                    var timeout = $item.data('timeout');
                    if (timeout) clearTimeout(timeout);
                    $item.data('timeout', setTimeout(function () {
                        $this.updateCurrentItem();
                    }, 100));
                });
            }
            this.activeItem.removeClass('active');
            if (this.activeItemType !== '')
                this.form[this.activeItemType].form.css('display', 'none');


            this.activeItem = $item;
            this.activeItemType = type;
            for (key in values) {
                var el = $('#item_' + type + key);
                if (el.length > 0) {
                    el.val(values[key]/*.replace(/\\n/g, "\n")*/);
                    $.fireEvent(el[0], 'change');
                }
            }

            this.form[this.activeItemType].form.css('display', 'block');

            this.form.select[0].selectedIndex = this.form.select.find('option').index(this.activeItem.data('ssoption'));

            this.activeItem.addClass('active');
            if (item != this.dummyitem[0])
                this.switchToItemTab();
        },
        updateCurrentItem: function () {
            var _this = this,
                data = {},
                odata = {},
                re = new RegExp('item_' + this.activeItemType + "\\[(.*?)\\]", ""),
                form = this.form[this.activeItemType],
                html = form.template,
                parser = null;
            if (scope['ssItemParser' + this.activeItemType] !== undefined) {
                parser = new scope['ssItemParser' + this.activeItemType];
            } else {
                parser = new scope['ssItemParser'];
            }
            form.fields.each(function () {
                var $el = $(this),
                    name = $el.attr('name').match(re)[1];
                data[name] = $el.val();
                odata[name] = data[name];
                data[name] = _this.fillItemWithSample(data[name]);
                var _data = parser.parse(name, data[name]);
                for (var k in _data) {
                    var reg = new RegExp('\\{' + k + '\\}', 'g');
                    html = html.replace(reg, _data[k]);
                    data[k] = _data[k];
                }
            });
            
            var helpers = this.activeItem.find('.ui-helper');
            $('<div />').append(helpers);
            this.activeItem.html(parser.render($(html
                .replace(/\{\{id\}\}/g, "nextend-smart-slider-0")
                .replace(/\{\{uuid\}\}/g, $.fn.uid())), data));
            this.activeItem.append(helpers);
            this.activeItem.ssdata('itemvalues', JSON.stringify(odata));
        },
        fillItemWithSample: function(value){
            return value;
        },
        _fillItemWithSample: function(value){
            try{
                return value.replace(/(\{nextend\|\|([a-zA-Z0-9,\|\|]+)\()?(\{\|(.*?)\-([0-9]+)\|\})(\)\})?/g, function(){
                    var i = parseInt(arguments[5])-1;
                    if(typeof window.samplegeneratordata[i] !== 'undefined' && typeof window.samplegeneratordata[i][arguments[4]] !== 'undefined'){
                        var s = window.samplegeneratordata[i][arguments[4]];
                        if(arguments[1] == ""){
                            return s;
                        }else{
                            var fns = arguments[2].split('||');
                            for (var i = fns.length - 1; i >= 0; i--) {
                                var fn = fns[i].split(',');
                                switch (fn[0]) {
                                    case 'cleanhtml':
                                        s = strip_tags(s, '<p><a><b><br><br/><i>');
                                        break;
                                    case 'removehtml':
                                        s = strip_tags(s);
                                        break;
                                    case 'splitbychars':
                                        s = s.substr(fn[1], fn[2])
                                        break;
                                    case 'splitbywords':
                                        var len = s.length;
                                        var pos = fn[2] > len ? len : s.indexOf(' ', fn[2]);
                                        if(pos == -1) pos = len;
                                        s = s.substr(0, pos);
                                        break;
                                    case 'findimage':
                                        var index = typeof fn[1] != 'undefined' ? parseInt(fn[1]) - 1 : 0;
                                        var re = /<img.*?src=[\'"](.*?)[\'"][^>]*>/gi,
                                            r = [],
                                            tmp = null;
                                        while(tmp = re.exec(s)){
                                            r.push(tmp[1]);
                                        };
                                        if (r.length && typeof r[index] != 'undefined') {
                                            s = r[index];
                                        } else {
                                            s = '';
                                        }
                                        break;
                    
                                }
                            }
                            // Validate not valid HTML
                            var nodes = njQuery.parseHTML('<div>'+s+'</div>', document, true);
                            return njQuery(nodes).html();
                        }
                    }
                    return arguments[0];
                });
            }catch(e){
                return value;
            }
        }
    });
})(njQuery, window);

function strip_tags(input, allowed) {
  allowed = (((allowed || '') + '')
    .toLowerCase()
    .match(/<[a-z][a-z0-9]*>/g) || [])
    .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '')
    .replace(tags, function($0, $1) {
      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}