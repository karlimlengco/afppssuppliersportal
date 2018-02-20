@section('title')
Transaction Summary
@stop

@section('styles')
<style type="text/css">
    .hidden, .dataTables_filter{
        display: none!important;
        visibility: hidden;
    }
    .hidden #datatable-responsive_wrapper{
        display: none!important;
        visibility: hidden;
    }
</style>
@stop

@section('contents')

    <psr></psr>

@stop
@section('scripts')

<script type="text/javascript">
    var ptype = 'bidding';

    $('#alternative').on('click', function(e){
        e.preventDefault();
        $('.button').toggleClass('button-unfocus')
        $('.alternative').show()
        $('.bidding').hide()
        var ptype = '';
    });

    $('#bidding').on('click', function(e){
        e.preventDefault();
        $('.button').toggleClass('button-unfocus')
        $('.alternative').hide()
        $('.bidding').removeClass('hidden')
        $('.bidding').show()
        var ptype = 'bidding';
    });


    var startDate,
        endDate,
        updateStartDate = function() {
            startPicker.setStartRange(startDate);
            endPicker.setStartRange(startDate);
            endPicker.setMinDate(startDate);
        },
        updateEndDate = function() {
            startPicker.setEndRange(endDate);
            startPicker.setMaxDate(endDate);
            endPicker.setEndRange(endDate);
        },
        startPicker = new Pikaday({
            field: document.getElementById('start'),
            minDate: new Date(2012, 12, 31),
            onSelect: function() {
                startDate = this.getDate();
                updateStartDate();
            }
        }),
        endPicker = new Pikaday({
            field: document.getElementById('end'),
            minDate: new Date(2012, 12, 31),
            onSelect: function() {
                endDate = this.getDate();
                updateEndDate();
            }
        }),
        _startDate = startPicker.getDate(),
        _endDate = endPicker.getDate();

        if (_startDate) {
            startDate = _startDate;
            updateStartDate();
        }

        if (_endDate) {
            endDate = _endDate;
            updateEndDate();
        }

</script>
@stop