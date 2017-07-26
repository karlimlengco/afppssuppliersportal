@section('title')
PhilGeps Posting
@stop


@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop
@section('contents')

{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>


<div class="row">
    <div class="twelve columns">
            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('rfq_id', 'RFQ Number', $rfq_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('philgeps_number', 'PhilGeps number') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('philgeps_posting', 'PhilGeps From Posting') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('deadline_rfq', 'Deadline To Submit RFQ') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('opening_time', 'Opening Time') !!}
                </div>
            </div>


        {!!Form::close()!!}
    </div>
</div>

@stop
@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    // datepicker
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-deadline_rfq'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-philgeps_posting'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker
</script>
@stop