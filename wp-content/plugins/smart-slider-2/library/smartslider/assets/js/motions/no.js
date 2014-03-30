(function ($, scope, undefined) {

    scope.ssAnimationNo = scope.ssAnimation.extend({
        init: function (layer, options) {
            this._super(layer, options);
        },
        _setInStart: function () {
            this.layer.css('display', 'block');
        },
        _animateIn: function () {
            this.endFN = 'onAnimateInEnd';
            this.layer.css('display', 'block');
            this['onAnimateInEnd']();
        },
        _animateOut: function () {
            this.endFN = 'onAnimateOutEnd';
            this['onAnimateOutEnd']();
        }
    });

    scope.ssAnimationManager.addAnimation('no', scope.ssAnimationNo, {});

})(njQuery, window);