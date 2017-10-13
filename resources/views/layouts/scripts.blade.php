<!-- jQuery -->
<script src="/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
{{-- <script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script> --}}

<script src="/js/app.js"></script>

<!-- bootstrap-daterangepicker -->
<script src="/js/moment/moment.min.js"></script>
<script src="/js/datepicker/daterangepicker.js"></script>
<script src="/js/selectize.js"></script>
<script src="/vendors/pickmeup/pickmeup.min.js"></script>


<!-- Datatables Scripts -->
<script src="/js/datatables/media/js/jquery.dataTables.js"></script>
<script src="/js/datatables/media/js/dataTables.bootstrap.js"></script>
<script src="/js/dataTables.keyTable.min.js"></script>
<script src="/js/dataTables.responsive.min.js"></script>
<script src="/js/pikaday/pikaday.js"></script>

<!-- My scripts -->
<script src="/js/main.js"></script>
<script src="/js/jquery-mousewheel.min.js"></script>
<script src="/js/jquery.mCustomScrollbar.js"></script>
<script src="/js/summernote.js"></script>

<script>
    (function($){
        $(window).on("load",function(){

            $(".sidebar").mCustomScrollbar({
                axis:"y",
                setTop:0,
                scrollbarPosition:"inside",
                scrollInertia:250,
                autoDraggerLength:true,
                autoHideScrollbar:true,
                alwaysShowScrollbar:0,
                contentTouchScroll:25,
                theme:"minimal-dark"
            });

            $(".table-scroll").mCustomScrollbar({
                axis:"x",
                setLeft:0,
                scrollbarPosition:"inside",
                scrollInertia:250,
                autoDraggerLength:true,
                autoHideScrollbar:true,
                alwaysShowScrollbar:0,
                contentTouchScroll:25,
                theme:"minimal-dark"
            });

            $(".chat__thread").mCustomScrollbar({
                axis:"y",
                setTop:"-999999999px",
                scrollbarPosition:"inside",
                scrollInertia:250,
                autoDraggerLength:true,
                autoHideScrollbar:true,
                alwaysShowScrollbar:0,
                contentTouchScroll:25,
                theme:"minimal-dark"
            });

        });
    })(jQuery);


    var picker = new Pikaday(
    {
        field: document.getElementById('datepicker'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


    /*!
     * Pikaday jQuery plugin.
     *
     * Copyright © 2013 David Bushell | BSD & MIT license | https://github.com/dbushell/Pikaday
     */

    (function (root, factory)
    {
        'use strict';

        if (typeof exports === 'object') {
            // CommonJS module
            factory(require('jquery'), require('pikaday'));
        } else if (typeof define === 'function' && define.amd) {
            // AMD. Register as an anonymous module.
            define(['jquery', 'pikaday'], factory);
        } else {
            // Browser globals
            factory(root.jQuery, root.Pikaday);
        }
    }(this, function ($, Pikaday)
    {
        'use strict';

        $.fn.pikaday = function()
        {
            var args = arguments;

            if (!args || !args.length) {
                args = [{ }];
            }

            return this.each(function()
            {
                var self   = $(this),
                    plugin = self.data('pikaday');

                if (!(plugin instanceof Pikaday)) {
                    if (typeof args[0] === 'object') {
                        var options = $.extend({}, args[0]);
                        options.field = self[0];
                        self.data('pikaday', new Pikaday(options));
                    }
                } else {
                    if (typeof args[0] === 'string' && typeof plugin[args[0]] === 'function') {
                        plugin[args[0]].apply(plugin, Array.prototype.slice.call(args,1));

                        if (args[0] === 'destroy') {
                            self.removeData('pikaday');
                        }
                    }
                }
            });
        };

    }));

    $('.datepicker').pikaday({
        firstDay: 1,
        format: 'YYYY-MM-DD',
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

</script>