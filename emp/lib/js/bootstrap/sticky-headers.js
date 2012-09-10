(function($) {

    $.fn.fixedHeader = function (options) {
        var config = {
            topOffset: 40,
            bgColor: '#EEEEEE'
        };
        if (options){ $.extend(config, options); }

        return this.each( function() {
            var o = $(this);

            var $win = $(window)
                , $head = $('thead.header', o)
                , isFixed = 0;
            var headTop = $head.length && $head.offset().top - config.topOffset;

            function processScroll() {
                if (!o.is(':visible')) return;
                var i, scrollTop = $win.scrollTop();
                var t = $head.length && $head.offset().top - config.topOffset;
                if (!isFixed && headTop != t) { headTop = t; }
                if (scrollTop >= headTop && !isFixed) {
                    isFixed = 1;
                    $head.addClass('header-fixed');
                } else if (scrollTop <= headTop && isFixed) {
                    isFixed = 0;
                    $head.removeClass('header-fixed');
                }
                isFixed ? $('thead.header-copy', o).removeClass('hide')
                    : $('thead.header-copy', o).addClass('hide');
            }
            $win.on('scroll', processScroll);

            // hack sad times - holdover until rewrite for 2.1
            $head.on('click', function () {
                if (!isFixed) setTimeout(function () {  $win.scrollTop($win.scrollTop() - 47) }, 10);
            })

            $head.clone().removeClass('header header-fixed').addClass('header-copy').appendTo(o);
            o.find('tbody > tr:first > td').each(function (i, h){
                var w = $(h).width();
                var d = $('<div>').css({overflow:'hidden', width: w});
                $(h).css({width: w});
                $head.find('> tr > th:eq('+i+')').append(d.clone());
            });
            $head.css({ margin:'0 auto',
                width: o.width(),
                'background-color':config.bgColor });
            processScroll();
        });
    };

})(jQuery);