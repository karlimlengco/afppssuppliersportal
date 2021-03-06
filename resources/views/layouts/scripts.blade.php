<!-- jQuery -->
<script type="text/javascript">

if (typeof Object.assign != 'function') {
  Object.assign = function(target) {
    'use strict';
    if (target == null) {
      throw new TypeError('Cannot convert undefined or null to object');
    }

    target = Object(target);
    for (var index = 1; index < arguments.length; index++) {
      var source = arguments[index];
      if (source != null) {
        for (var key in source) {
          if (Object.prototype.hasOwnProperty.call(source, key)) {
            target[key] = source[key];
          }
        }
      }
    }
    return target;
  };
  if (!Object.entries) {

    Object.entries = function( obj ){
      var ownProps = Object.keys( obj ),
          i = ownProps.length,
          resArray = new Array(i); // preallocate the Array
      while (i--)
        resArray[i] = [ownProps[i], obj[ownProps[i]]];

      return resArray;
    };
  }
}
</script>

<!-- Minified version of `es6-promise-auto` below. -->
<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
<script src="/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
{{-- <script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script> --}}

{{-- <script src="/js/app.js"></script> --}}

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

        $('.sidebar__child-menu__item__link').on("click",function() {
            $('.preloader').show();
        });

        $(window).on("load",function(){

            $('.preloader').fadeOut(1000,function(){
                $(this).hide();
            });

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
                // autoHideScrollbar:true,
                alwaysShowScrollbar:1,
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
        firstDay: 1
    });


    /*!
     * Pikaday jQuery plugin.
     *
     * Copyright ?? 2013 David Bushell | BSD & MIT license | https://github.com/dbushell/Pikaday
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
        setDefaultDate: new Date()
    });

    
    $(window).on("load", function(){
      var content = $('.o-content');
      var topbarHeight = $(".p-topbar").outerHeight(true);
      var bottombarHeight = $('.p-bottombar').outerHeight(true);
      var sidebarWidth = $('.p-sidebar').outerWidth(true);
      var toolbar = $('.p-toolbar');
      var toolbarWidth = $('.p-toolbar').outerWidth(true);
      var sidebarToggleButton = $('.js-toggle-sidebar-button');
      var sidebarShowButton  = $('.js-show-sidebar-button');
      var sidebarHideButton  = $('.js-hide-sidebar-button');
      var modal = $('.p-modal');
      var openModal = $('.js-open-modal');
      var closeModal = $('.js-close-modal');

      content.css({
        'padding-top': topbarHeight,
        'padding-bottom': bottombarHeight,
        'padding-right': toolbarWidth
      })

      toolbar.css({
        'padding-top': topbarHeight,
        'padding-bottom': bottombarHeight
      })

      $('.p-drawer').css({
        'padding-top': topbarHeight,
        'padding-bottom': bottombarHeight
      })

      sidebarToggleButton.on("click", function(){
        $(this).find('i').toggleClass('arrows-1_tail-left arrows-1_tail-right');
        $('.o-container').toggleClass('js-toggle-sidebar');
      })

      sidebarShowButton.on("click", function(){
        $(this).toggleClass('js-show-sidebar-button--float');
        $('o-body').addClass('u-no-scroll-y');
        $('.p-sidebar').addClass('p-sidebar--active');
      })

      sidebarHideButton.on("click", function(){
        $('o-body').removeClass('u-no-scroll-y');
        $('.p-sidebar').removeClass('p-sidebar--active');
      })

      /*
       modal
       */
      openModal.on("click", function(){
        modal.addClass('p-modal--active');
      })

      closeModal.on("click", function(){
        modal.removeClass('p-modal--active');
      })

      /*
       toggle status
       */
      $('.js-toggle-status').on('click', function(){
        $(this).parents('.c-dropdown').toggleClass('c-dropdown--active');
      })

      /*
       toggle action menu
       */
      $('.js-toggle-settings').on('click', function(){
        $(this).parents('.c-dropdown').toggleClass('c-dropdown--active');
      })

      /*
       toggle post menu
       */
      $('.js-toggle-post-menu').on('click', function(){
        $(this).parents('.c-dropdown').toggleClass('c-dropdown--active');
      })

      /*
      show comments drawer
       */
      $('.js-show-comments-drawer').on('click', function(){
        $('.js-comments-drawer').addClass('p-drawer--active');
      })

      $('.js-hide-comments-drawer').on('click', function(){
        $('.js-comments-drawer').removeClass('p-drawer--active');
      })

      /*
       show user settings menu
       */
      $('.js-show-user-settings').on('click', function(){
        $(this).parents('.c-dropdown').toggleClass('c-dropdown--active');
      })

      /*
       show notifications
       */
      $('.js-show-notifications').on('click', function(){
        $(this).parents('.c-dropdown').toggleClass('c-dropdown--active');
      })

      /*
       settings dropdown
       */
      $('.js-dropdown-trigger').on('click', function(){
        $(this).parents('.c-dropdown').toggleClass('c-dropdown--active');
      })
    })

</script>