@section('title')
Unit Purchase Request Import Validate
@stop

@section('modal')
    {{-- @include('modules.partials.new_account_code') --}}
    @include('modules.partials.create_signatory')
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
                    {!! Form::textField('project_name', 'Project Name') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('upr_number', 'UPR Number') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('place_of_delivery', 'Place Of Delivery') !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('date_prepared', 'Date Prepared') !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('units', 'Units', $unit) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('procurement_type', 'Procurement Type', $procurement_types) !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {{-- {!! Form::selectField('procurement_office', 'Procurement Center / Contracting Office', $procurement_center ) !!} --}}
                    {!! Form::selectField('procurement_office', 'Procurement Center / Contracting Office', $procurement_center, ($user->units) ? $user->units->pcco_id : "" ) !!}


                </div>
                <div class="four columns">
                    {!! Form::selectField('mode_of_procurement', 'Mode of Procurement', ['public_bidding' => 'Public Bidding'] + $procurement_modes) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('chargeability', 'Chargeability', $charges) !!}
                </div>
            </div>

          {{--   <div class="row">
                <div class="six columns">
                    <div class="form-group">
                        <label>Old Account Code</label>

                    {!! Form::select('old_account_code', $old_codes, null, ['id' => 'id-field-old_account_code', 'data-selectize' => 'selectField']) !!}
                    </div>
                </div>
                <div class="six columns">
                    {!! Form::selectField('new_account_code', 'New Account Code', $account_codes) !!}

                    <div class="form-group">
                        <label>New Account Code</label>

                    {!! Form::select('new_account_code', $account_codes, null, ['id' => 'id-field-new_account_code', 'data-selectize' => 'selectField']) !!}
                    </div>
                </div>
            </div> --}}
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


            <div class="row">
                <div class="four columns">
                    <div class="form-group">
                        <label class="label">Request By</label>
                        {!! Form::select('requestor_id',  ['' => 'Select One']+$signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-requestor_id']) !!}
                    </div>
                </div>
                <div class="four columns">

                    <label class="label">Fund Certified Available</label>
                    {!! Form::select('fund_signatory_id',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-fund_signatory_id']) !!}
                    {{-- {!! Form::selectField('fund_signatory_id', 'Fund Certified Available', $signatory_list) !!} --}}
                </div>
                <div class="four columns">
                    <label class="label">Approved By</label>
                    {!! Form::select('approver_id',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-approver_id']) !!}
                    {{-- {!! Form::selectField('approver_id', 'Approved By', $signatory_list) !!} --}}
                </div>
            </div>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th width="30%">Description</th>
                    <th width="20%">Account Code</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Unit</th>
                    <th width="15%">Unit Price</th>
                    <th width="15%">Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td> {!! Form::text('item_description[]',  $item['item_description'], ['class' => 'input', 'id' => 'item_description']) !!} </td>
                    <td>
                        {!! Form::select('new_account_code[]', $account_codes, $item['new_account_code'], ['class'=>'select']) !!}

                        {{-- {!! Form::text('new_account_code[]', $item['new_account_code'], ['class' => 'input', 'id' => 'new_account_code']) !!} --}}
                    </td>
                    <td> {!! Form::text('quantity[]', $item['quantity'], ['class' => 'input unit_price', 'id' => 'quantity']) !!} </td>
                    <td> {!! Form::text('unit_measurement[]', $item['unit'], ['class' => 'input', 'id' => 'unit_measurement']) !!} </td>
                    <td> {!! Form::text('unit_price[]', $item['unit_price'], ['class' => 'input unit_price', 'id' => 'unit_price']) !!} </td>
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
    // $('.unit_price').on('keypress', function(evt){
    //   if (evt.which < 48 || evt.which > 57)
    //       {
    //           evt.preventDefault();
    //       }
    // })
    var xhr;
    // var select_state, $select_state;
    // var select_city, $select_city;
    // // $select_state = $('#id-field-old_account_code').selectize({
    // //     onChange: function(value) {
    // //         select_city.addItem(value, false);
    // //     }
    // // });

    // // $select_city = $('#id-field-new_account_code').selectize({
    // //     onChange: function(value) {
    // //         select_state.addItem(value, false);
    // //     }
    // // });

    // select_city  = $select_city[0].selectize;
    // select_state = $select_state[0].selectize;



    $requestor = $('#id-field-requestor_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $funder = $('#id-field-fund_signatory_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $approver = $('#id-field-approver_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    requestor  = $requestor[0].selectize;
    funder  = $funder[0].selectize;
    approver  = $approver[0].selectize;

    $(document).on('submit', '#create-signatory-form', function(e){
        e.preventDefault();
        var inputs =  $("#create-signatory-form").serialize();

        console.log(inputs);
        $.ajax({
            type: "POST",
            url: '/api/signatories/store',
            data: inputs,
            success: function(result) {
                console.log(result);
                requestor.addOption({value:result.id, text: result.name});
                funder.addOption({value:result.id, text: result.name});
                approver.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_prepared'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop
