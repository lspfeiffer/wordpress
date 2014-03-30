;
(function($, scope, undefined) {
    scope.ssadminConsoleClass = NClass.extend({
        timeout: null,
        priority: 1,
        init: function(options) {
            this.c = options.console[0];
            window[options.name] = this;
        },
        set: function(msg, priority, timeout) {
            priority = typeof priority !== 'undefined' ? priority : 1;
            if (this.priority > priority)
                return false;
            
            this.priority = priority;

            timeout = typeof timeout !== 'undefined' ? timeout : 3000;
            if (this.timeout)
                clearTimeout(this.timeout);

            this.c.innerHTML = msg;

            if (timeout != 0) {
                var $this = this;
                this.timeout = setTimeout(function() {
                    $this.c.innerHTML = '';
                    $this.priority = 1;
                }, timeout);
            }
        }
    });

    $(document).ready(function() {
        new scope.ssadminConsoleClass({
            'console': $('.smartslider-slide-console'),
            name: 'slideconsole'
        });
    });
})(njQuery, window);