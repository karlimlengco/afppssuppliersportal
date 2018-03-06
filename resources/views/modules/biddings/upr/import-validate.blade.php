@section('title')
Unit Purchase Request Import Validate
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
    <style type="text/css">
        #item_table td{
            padding:0;

        }
    </style>
@stop

@section('contents')

{!! Form::model($data, ['route' => 'procurements.unit-purchase-requests.save-file']) !!}
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <div class="row">
                <div class="four columns">
                    {!! Form::textField('project_name', 'Project Name / Activity') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('upr_number', 'UPR Number') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('place_of_delivery', 'Place Of Delivery') !!}
                </div>
            </div>

            <div class="row">
                <div class="three columns">
                    {!! Form::textField('date_prepared', 'Date Prepared') !!}
                </div>
                <div class="three columns">
                    {!! Form::textField('date_processed', 'Date Processed') !!}
                </div>
                <div class="three columns">
                    {!! Form::selectField('units', 'Units', $unit) !!}
                </div>
                <div class="three columns">
                    {!! Form::selectField('procurement_type', 'Procurement Type', $procurement_types) !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('procurement_office', 'Procurement Office', $procurement_center) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('mode_of_procurement', 'Mode of Procurement', ['public_bidding' => 'Public Bidding'] + $procurement_modes) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('chargeability', 'Chargeability', $charges) !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    <div class="form-group">
                        <label>Old Account Code</label>

                    {!! Form::select('old_account_code', $old_codes, null, ['id' => 'id-field-old_account_code', 'data-selectize' => 'selectField']) !!}
                    </div>
                </div>
                <div class="six columns">
                    {{-- {!! Form::selectField('new_account_code', 'New Account Code', $account_codes) !!} --}}

                    <div class="form-group">
                        <label>New Account Code</label>

                    {!! Form::select('new_account_code', $account_codes, null, ['id' => 'id-field-new_account_code', 'data-selectize' => 'selectField']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="six columns">
                    {!! Form::textField('fund_validity', 'Fund Validity') !!}
                </div>
                <div class="six columns">
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
                    <td> {!! Form::text('item_description[]',  $item['item_description'], ['class' => 'input', 'id' => 'item_description']) !!} </td>
                    <td> {!! Form::text('quantity[]', $item['quantity'], ['class' => 'input', 'id' => 'quantity']) !!} </td>
                    <td> {!! Form::text('unit_measurement[]', $item['unit'], ['class' => 'input', 'id' => 'unit_measurement']) !!} </td>
                    <td> {!! Form::text('unit_price[]', $item['unit_price'], ['class' => 'input', 'id' => 'unit_price']) !!} </td>
                    <td> {!! Form::text('total_amount[]', $item['total_amount'], ['class' => 'input', 'id' => 'total_amount', 'readonly']) !!} </td>
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
    var xhr;
    var select_state, $select_state;
    var select_city, $select_city;
    $select_state = $('#id-field-old_account_code').selectize({
        onChange: function(value) {
            select_city.addItem(value, false);
        }
    });

    $select_city = $('#id-field-new_account_code').selectize({
        onChange: function(value) {
            select_state.addItem(value, false);
        }
    });

    select_city  = $select_city[0].selectize;
    select_state = $select_state[0].selectize;

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_prepared'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_processed'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop
