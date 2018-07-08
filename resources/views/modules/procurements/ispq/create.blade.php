@section('title')
Invitation to Submit Price Quotation
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
        <button type="submit" id="save-button" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <div class="row">
            <div class="four columns">
                {!! Form::textField('canvassing_date', 'Canvass Date') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('canvassing_time', 'Canvass Time') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('transaction_date', 'Transaction Date') !!}
            </div>
        </div>

        <div class="row">
            <div class="twelve columns">
                {!! Form::selectField('signatory_id', 'Signatories', $signatory_lists) !!}
            </div>
        </div>

        <div class="row">
            <div class="twelve columns">
                {!! Form::tagField('items', 'RFQ', $rfq_list) !!}
            </div>
        </div>

        <div class="row">
            <div class="twelve columns">
                {!! Form::textareaField('venue', 'Venue', null, ['rows'=>3]) !!}
            </div>
        </div>

    </div>
</div>
{!!Form::close()!!}

@stop
@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    $(document).on("click", "#save-button",function() {
        setTimeout(function () { $("#save-button").prop('disabled',true); }, 0);
    });

    var timepicker1 = new TimePicker(['id-field-canvassing_time'], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker1.on('change', function(evt){
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

    // datepicker
    var canvassing_date = new Pikaday(
    {
        field: document.getElementById('id-field-canvassing_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker
</script>
@stop