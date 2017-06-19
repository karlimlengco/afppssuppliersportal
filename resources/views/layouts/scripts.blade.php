<!-- jQuery -->
<script src="/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
{{-- <script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script> --}}


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

        });
    })(jQuery);


    var picker = new Pikaday(
    {
        field: document.getElementById('datepicker'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>