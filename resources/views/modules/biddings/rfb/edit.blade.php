@section('title')
Request For Bid
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.delete')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="button" id="edit-button" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="#" class="button topbar__utility__button--modal" ><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('rfb_number', "Ref Number", null, ['disabled' => 'disabled']) !!}
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

            @if(!$data->completed_at)
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('deadline', 'Deadline to submit') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('opening_time', 'Opening Time') !!}
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="four columns">
                        {!! Form::selectField('bac_id', 'BacSec', $bacsec_list) !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('released_date', 'Released Date') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('received_date', 'Received Date') !!}
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('remarks', 'Remarks', null, ['rows' => '3']) !!}
                </div>
            </div>
    </div>
</div>

{!! Form::close() !!}

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })

    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    // datepicker
    // pickmeup('#id-field-transaction_date', {
    //     format  : 'Y-m-d',
    //     default_date: false
    // });

    // pickmeup('#id-field-deadline', {
    //     format  : 'Y-m-d',
    //     default_date: false
    // });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var completed_at = new Pikaday(
    {
        field: document.getElementById('id-field-completed_at'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-deadline'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop