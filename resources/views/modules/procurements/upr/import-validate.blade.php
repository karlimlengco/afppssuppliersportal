@section('title')
Unit Purchase Request Import Validate
@stop

@section('contents')

{!! Form::model($data, ['routes' => 'procurements.unit-purchase-requests.import-file']) !!}
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
                    {!! Form::textField('project_name', 'Project Name') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('upr_number', 'UPR Number') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('date_prepared', 'Date Prepared') !!}
                </div>
                <div class="six columns">
                    {!! Form::selectField('units', 'Units', $unit) !!}
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
                    {!! Form::textareaField('purpose', 'Purpose of Purchase', null, ['rows' => 3]) !!}
                </div>
                <div class="six columns">
                    {!! Form::textareaField('other_infos', 'Other Essential Info', null, ['rows' => 3]) !!}
                </div>
            </div>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td> {!! Form::text('a_item_description',  $item['item_description'], ['class' => 'input', 'id' => 'item_description']) !!} </td>
                    <td> {!! Form::text('a_quantity', $item['quantity'], ['class' => 'input', 'id' => 'quantity']) !!} </td>
                    <td> {!! Form::text('a_unit_measurement', $item['unit'], ['class' => 'input', 'id' => 'unit_measurement']) !!} </td>
                    <td> {!! Form::text('a_unit_price', $item['unit_price'], ['class' => 'input', 'id' => 'unit_price']) !!} </td>
                    <td> {!! Form::text('a_total_amount', $item['total_amount'], ['class' => 'input', 'id' => 'total_amount', 'readonly']) !!} </td>
                    {{-- <td> <button type="button" class="button" id="add_item">add</button> </td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{!!Form::close()!!}
@stop

@section('scripts')
<script>

</script>
@stop
