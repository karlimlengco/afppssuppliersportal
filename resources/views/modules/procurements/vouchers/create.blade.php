@section('title')
Vouchers
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open($modelConfig['store']) !!}

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('rfq_id', 'RFQ Number', $rfq_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('bir_address', 'BIR Address', null, ['rows'=>3]) !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::numberField('final_tax', 'Final') !!}
                </div>
                <div class="six columns">
                    {!! Form::numberField('expanded_witholding_tax', 'Expanded Witholding Tax') !!}
                </div>
            </div>

            <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop
@section('scripts')
<script type="text/javascript">

    // datepicker
    // pickmeup('#id-field-transaction_date', {
    //     format  : 'Y-m-d'
    // });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker
</script>
@stop