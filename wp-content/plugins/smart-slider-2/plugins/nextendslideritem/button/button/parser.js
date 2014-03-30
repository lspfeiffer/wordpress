(function($, scope, undefined) {
    scope.ssItemParserbutton = scope.ssItemParser.extend({
        parse: function(name, data){
            var o = this._super(name, data);
            if(name === 'link'){
                var _d = data.split('|*|');
                o.url = _d[0];
                o.target = _d[1];
                delete o.size;
            }
            return o;
        }
    });
})(njQuery, window);