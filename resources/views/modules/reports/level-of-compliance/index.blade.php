@section('title')
Level of Compliance Report
@stop

@section('styles')
@stop

@section('contents')
<div class="row">
    <div class="six columns align-left">
      <h1>Pick Date Range</h1>
    </div>
    <div class="six columns align-right pull-right">
      {!! Form::open(['route' => 'reports.level-of-compliance.store']) !!}
        <div style="display: inline-block">
            <input type="text" id="start" name="date_from" class="input" placeholder="Start Date">
        </div>

        <div style="display: inline-block">
            <input type="text" id="end" name="date_to" class="input" placeholder="End Date">
        </div>
        <button class="button" style="margin-bottom:10px"><span class="nc-icon-mini arrows-e_archive-e-download"></span></button>
        {!!Form::close()!!}
    </div>
</div>
@stop

@section('scripts')

<script type="text/javascript">
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