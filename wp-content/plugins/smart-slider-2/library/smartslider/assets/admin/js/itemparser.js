(function($, scope, undefined) {
    scope.ssItemParser = NClass.extend({
        parse: function(name, data){
            var o = {};
            o[name] = data;
            //o[name+'_esc'] = data.replace(/"/g, '&quot;').replace(/'/g, '&apos;');
            return o;
        },
        render: function(node, data){
            return node;
        }
    });
})(njQuery, window);