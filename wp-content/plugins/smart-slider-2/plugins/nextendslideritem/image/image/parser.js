(function($, scope, undefined) {
    scope.ssItemParserimage = scope.ssItemParser.extend({
        parse: function(name, data){
            var o = this._super(name, data);
            if(name === 'size'){
                var _d = data.split('|*|');
                o.width = _d[0];
                o.height = _d[1];
                delete o.size;
            }
            else if(name === 'link'){
                var _d = data.split('|*|');
                o.url = _d[0];
                o.target = _d[1];
                delete o.size;
            }else if(name === 'kenburns'){
                var _d = data.split('|*|');
                if(parseInt(_d[0])){
                    o.kenburnsclass = 'haskenburns ';
                }else{
                    o.kenburnsclass = '';
                }
            }
            return o;
        },
        render: function(node, data){
            if(data['url'] == '#'){
                node.html(node.children('a').html());
            }
            return node;
        }
    });
})(njQuery, window);
