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
                    {!! Form::selectField('procurement_type', 'Procurement Program/Project', $procurement_types) !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('procurement_office', 'Procurement Center / Contracting Office', $procurement_center) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('mode_of_procurement', 'Mode of Procurement', ['public_bidding' => 'Public Bidding']+$procurement_modes) !!}
                </div>
                <div class="four columns">
                    {!! Form::selectField('chargeability', 'Chargeability', $charges) !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('new_account_code', 'New Account Code', $account_codes) !!}
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
                            <tr>
                                <td> {!! Form::text('a_item_description', null, ['class' => 'input', 'id' => 'item_description']) !!} </td>
                                <td> {!! Form::text('a_quantity', null, ['class' => 'input', 'id' => 'quantity']) !!} </td>
                                <td> {!! Form::text('a_unit_measurement', null, ['class' => 'input', 'id' => 'unit_measurement']) !!} </td>
                                <td> {!! Form::text('a_unit_price', null, ['class' => 'input', 'id' => 'unit_price']) !!} </td>
                                <td> {!! Form::text('a_total_amount', null, ['class' => 'input', 'id' => 'total_amount', 'readonly']) !!} </td>
                                <td> <button type="button" class="button" id="add_item">add</button> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

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
    // End Add item button

    // onchange quantity
    $(document).on('change', '#quantity', function(e){
        var quants  = $("#quantity").val();
        var price   = $("#unit_price").val();

        $("#total_amount").val(0)

        if(quants != "" && price != "")
        {
            changeTotalAmount(quants, price);
        }
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
        total_amount    =   quantity * price;
        console.log(total_amount);
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
        // end vars

        var table=document.getElementById("item_table");
        var table_len=(table.rows.length)-1;

        var newRow = "<tr id='row" + table_len + "'>";
            newRow += "<td id='desciption_row"+table_len+"'>";
            newRow += "<input type='text' name='item_description[]' value='"+item_desc+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='quantity_row"+table_len+"'>";
            newRow += "<input readonly type='text' name='quantity[]' value='"+quantity+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='unit_measurement_row"+table_len+"'>";
            newRow += "<input readonly type='text' name='unit_measurement[]' value='"+unit_measurement+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='unit_price_row"+table_len+"'>";
            newRow += "<input readonly type='text' name='unit_price[]' value='"+unit_price+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input readonly type='text' name='total_amount[]' value='"+total_amount+"' class='input' readonly/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input readonly type='button' value='Delete' class='button delete' onclick='delete_row("+table_len+")'";
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
</script>
@stop
