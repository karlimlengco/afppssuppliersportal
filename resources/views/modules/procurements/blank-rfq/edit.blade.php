@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="six columns align-left">
        <h3>Request For Quotation</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::model($data, $modelConfig['update']) !!}

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('rfq_number', "RFQ Number", null, ['disabled' => 'disabled']) !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('upr_id', 'UPR Number', $upr_list, null, ['disabled' => 'disabled']) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('deadline', 'Deadline to submit') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('opening_time', 'Opening Time') !!}
                </div>
            </div>

            <div class="row">

                <div class="six columns">
                    <button type="button" class="button"> <a href="{{route($indexRoute)}}"> Back </a></button>
                    <button class="button">Save</button>
                </div>

                <div class="six columns align-right">
                    <button class="button topbar__utility__button--modal" >Delete</button>
                </div>

            </div>
        {!! Form::close() !!}
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
    pickmeup('#id-field-transaction_date', {
        format  : 'Y-m-d',
        default_date: false
    });

    pickmeup('#id-field-deadline', {
        format  : 'Y-m-d',
        default_date: false
    });
    // end datepicker
</script>
@stop