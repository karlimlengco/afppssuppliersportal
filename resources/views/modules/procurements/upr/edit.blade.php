@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')

{!! Form::model($data, $modelConfig['update']) !!}
<div class="row">
    <div class="six columns align-left">
        <h3>Unit Purchase Request</h3>
    </div>
    <div class="six columns align-right">
        <button type="reset" class="button"> <a href="{{route($indexRoute,$data->id)}}">Back</a> </button>
        <button type="submit" class="button">Save</button>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <div class="row">
            <div class="four columns">
                {!! Form::textField('upr_number', 'UPR Number') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('afpps_ref_number', 'AFPPS Number') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('date_prepared', 'Date Prepared') !!}
            </div>
        </div>

        <div class="row">
            <div class="four columns">
                {!! Form::selectField('place_of_delivery', 'Place of delivery', $procurement_center) !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('mode_of_procurement', 'Mode of Procurement', $procurement_modes) !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('chargeability', 'Chargeability', $charges) !!}
            </div>
        </div>

        <div class="row">
            <div class="four columns">
                {!! Form::selectField('account_code', 'Account Code', $account_codes) !!}
            </div>
            <div class="four columns">
                {!! Form::textField('fund_validity', 'Fund Validity') !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('terms_of_payment', 'Terms of Payment', $payment_terms) !!}
            </div>
        </div>

        <div class="row">
            <div class="six columns">
                {!! Form::textareaField('purpose', 'Purpose of Purchase') !!}
            </div>
            <div class="six columns">
                {!! Form::textareaField('other_infos', 'Other Essential Info') !!}
            </div>
        </div>

    </div>
</div>

{!!Form::close()!!}

@stop


@section('scripts')
<script>
    // datepicker
    pickmeup('#id-field-date_prepared', {
        format  : 'Y-m-d'
    });
    // end datepicker
</script>
@stop