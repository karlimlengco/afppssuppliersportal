@section('title')
Unit Purchase Request
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

@section('modal')
    {{-- @include('modules.partials.new_account_code') --}}
    @include('modules.partials.create_signatory')
@stop

@section('styles')
    <style type="text/css">
        #item_table td{
            padding:0;

        }
    </style>
@stop

@section('contents')

{!! Form::open($modelConfig['store']) !!}
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button id="submit-button" type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>
<div class="row">
    <div class="twelve columns">
            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('procurement_office', 'Procurement Center / Contracting Office', $procurement_center, ($user->units) ? $user->units->pcco_id : "" ) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('units', 'Units', $unit, ($user) ? $user->unit_id : "") !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('project_name', 'Project Name') !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('upr_number', 'UPR Number') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('place_of_delivery', 'Place Of Delivery') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('date_prepared', 'Date Prepared') !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('procurement_type', 'Procurement Program/Project', $procurement_types) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('mode_of_procurement', 'Mode of Procurement', ['public_bidding' => 'Public Bidding'] + $procurement_modes) !!}
                </div>
                <div class="four columns">
                    {{-- {!! Form::selectField('chargeability', 'Chargeability', $charges) !!} --}}
                    {!! Form::textField('chargeability', 'Chargeability') !!}
                </div>
            </div>

       {{--      <div class="row">
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
                        {!! Form::select('requestor_id',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-requestor_id']) !!}
                    </div>
                </div>
                <div class="four columns">

                    <label class="label">Fund Certified Available</label>
                    {!! Form::select('fund_signatory_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-fund_signatory_id']) !!}
                    {{-- {!! Form::selectField('fund_signatory_id', 'Fund Certified Available', $signatory_list) !!} --}}
                </div>
                <div class="four columns">
                    <label class="label">Approved By</label>
                    {!! Form::select('approver_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-approver_id']) !!}
                    {{-- {!! Form::selectField('approver_id', 'Approved By', $signatory_list) !!} --}}
                </div>
            </div>

            <br>
            <div class="row">
                <div class="six columns">
                    <h3>Items</h3>
                </div>
                {{-- <div class="six columns align-right">
                    <button type="button" class="button" id="add_item">Add Item</button>
                </div> --}}
            </div>
            <br>

            {{-- <div class="row">
                <div class="twelve columns">
                    <table class='table' id="item_table">
                        <thead>
                            <tr>
                                <th width="30%">Description</th>
                                <th width="20%">Account Code</th>
                                <th width="5%">Qty</th>
                                <th width="10%">Unit</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Amount</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody> --}}
                            <line-items
                              :codes="{{ json_encode($account_codes) }}"
                              :old="{{ old() ? json_encode(old()) : '{}' }}"
                              :readonly="false">
                            </line-items>
                            {{-- <tr>
                                <td> {!! Form::text('a_item_description', null, ['class' => 'input', 'id' => 'item_description']) !!} </td>
                                <td>
                                    {!! Form::select('new_account_code[]', $account_codes,null, ['class'=>'select', 'id' => 'new_account_code']) !!}
                                </td>
                                <td> {!! Form::text('a_quantity', null, ['class' => 'input unit_price', 'id' => 'quantity']) !!} </td>
                                <td> {!! Form::text('a_unit_measurement', null, ['class' => 'input', 'id' => 'unit_measurement']) !!} </td>
                                <td> {!! Form::text('a_unit_price', null, ['class' => 'input unit_price', 'id' => 'unit_price']) !!} </td>
                                <td> {!! Form::text('a_total_amount', null, ['class' => 'input', 'id' => 'total_amount', 'readonly']) !!} </td>
                                <td> <button type="button" class="button" id="add_item">add</button> </td>
                            </tr> --}}
                      {{--   </tbody>
                    </table>
                </div>
            </div> --}}

    </div>
</div>

{!!Form::close()!!}

@stop

@section('scripts')
<script>

    // datepicker
    // pickmeup('#id-field-date_prepared', {
    //     format  : 'Y-m-d'
    // });


    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_prepared'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker

    // Add item button
    $(document).on('click', '#add_item', function(e){
        e.preventDefault();
        if( $("#item_description").val() != "" && $("#quantity").val() != "" && $("#unit_measurement").val() != "" && $("#unit_price").val() != "")
        {
            addField();
        }
        else
        {
            alert('fill up all fields');
        }
    });

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

    // End Add item button

    // // onchange quantity
    // $(document).on('click', '#submit-button', function(e){
    //   e.preventDefault()
    //   alert('asds');
    //   console.log( $("input.item_description") )
    // });

    // onchange quantity
    $(document).on('change', '.quantity', function(e){
        update_amounts();
    });
    // end onchange quantity
    //
    // onchange unit_price
    $(document).on('change', '#unit_price', function(e){
        var quants  = $("#quantity").val();
        var price   = $("#unit_price").val();

        $("#total_amount").val(0)

        if(quants != "" && price != "")
        {
            changeTotalAmount(quants, price);
        }

    });
    // end onchange unit_price

    // Change total Amount
    function changeTotalAmount(quantity, price)
    {
        price = price.replace(/,/g , "");
        total_amount    =   quantity * price;
        var total_amount    = $("#total_amount").val(total_amount);
    }
    // Change total Amount

    function addField()
    {
        // vars
        var item_desc       = $("#item_description").val();
        var quantity        = $("#quantity").val();
        var unit_measurement= $("#unit_measurement").val();
        var unit_price      = $("#unit_price").val();
        var total_amount    = $("#total_amount").val();
        var new_account_code= $("#new_account_code").val();
        var new_account_codetx= $("#new_account_code option:selected").text();

        console.log(new_account_codetx);
        // end vars

        var table=document.getElementById("item_table");
        var table_len=(table.rows.length)-1;

        var newRow = "<tr id='row" + table_len + "'>";
            newRow += "<td id='desciption_row"+table_len+"'>";
            newRow += "<input type='text' name='item_description[]' value='"+item_desc+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='new_account_code_row"+table_len+"'>";

            newRow += "<select type='text' name='new_account_code[]'  class='select'>";

            newRow += "<option value='"+new_account_code+"'>";
            newRow += new_account_codetx;
            newRow += "</option>";
            newRow += "</select>";
            // newRow += "<input type='text' name='new_account_code[]' value='"+new_account_code+"' class='input'/>";

            newRow += "<td id='quantity_row"+table_len+"'>";
            newRow += "<input type='text' name='quantity[]' value='"+quantity+"' class='input quantity'/>";
            newRow += "</td>";
            newRow += "<td id='unit_measurement_row"+table_len+"'>";
            newRow += "<input type='text' name='unit_measurement[]' value='"+unit_measurement+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='unit_price_row"+table_len+"'>";
            newRow += "<input type='text' name='unit_price[]' value='"+unit_price+"' class='input unit_price'/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input type='text' name='total_amount[]' value='"+total_amount+"' class='input total_amount' readonly/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input type='button' value='Delete' class='button delete' onclick='delete_row("+table_len+")'";
            newRow += "</td>";
            newRow += "</tr>";

        table.insertRow(table_len).outerHTML=newRow;

        $("#item_description").val("")
        $("#quantity").val("")
        $("#unit_measurement").val("")
        $("#unit_price").val("")
        $("#total_amount").val("")
    }

    function delete_row(no)
    {
        console.log(no);
         document.getElementById("row"+no+"").outerHTML="";
    }

    function update_amounts()
    {
        var sum = 0.0;
        $('#myTable > tbody  > tr').each(function() {
            var qty = $(this).find('.quantity').val();
            var price = $(this).find('.unit_price').val();
            var amount = (qty*price)
            sum+=amount;
            $(this).find('.total_amount').text(''+amount);
        });
        console.log('asds')
        //just update the total to sum
        // $('.total').text(sum);
    }


    // var xhr;
    // var select_state, $select_state;
    // var select_city, $select_city;
    // $select_state = $('#id-field-old_account_code').selectize({
    //     onChange: function(value) {
    //         select_city.addItem(value, false);
    //     }
    // });

    // $select_city = $('#id-field-new_account_code').selectize({
    //     onChange: function(value) {
    //         select_state.addItem(value, false);
    //     }
    // });

    // select_city  = $select_city[0].selectize;
    // select_state = $select_state[0].selectize;

      // $('.unit_price').on('keypress', function(evt){
    //   if (evt.which < 48 || evt.which > 57)
    //       {
    //           evt.preventDefault();
    //       }
    // })
</script>
@stop
