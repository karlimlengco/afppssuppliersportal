<!-- jQuery -->
<script src="/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="/vendors/nprogress/nprogress.js"></script>
<!-- bootstrap-progressbar -->
<script src="/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>

<!-- bootstrap-daterangepicker -->
<script src="/js/moment/moment.min.js"></script>
<script src="/js/datepicker/daterangepicker.js"></script>
<script src="/js/selectize.js"></script>

<!-- Custom Theme Scripts -->
<script src="/vendors/gentelella/build/js/custom.min.js"></script>

<!-- Datatables Scripts -->
<script src="/js/datatables/media/js/jquery.dataTables.js"></script>
<script src="/js/datatables/media/js/dataTables.bootstrap.js"></script>
<script src="/js/dataTables.keyTable.min.js"></script>
<script src="/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
    $('.selectize').each(function () {
        $(this).selectize();
    });

    $('.tagging').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
</script>