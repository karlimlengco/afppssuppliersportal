@section('title')
Purchase Order
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
    <style type="text/css">
        #item_table td{
            padding:0;

        }
    </style>
@stop

@section('contents')
{!! Form::open($modelConfig['store']) !!}

<div class="twelve columns utility utility--align-right" >
    <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    <button type="submit" class="button"><i class="nc-icon-mini ui-2_disk"></i></button>
</div>

<div class="row">
    <div class="twelve columns">
        <div class="row">
            <div class="three columns">
                {!! Form::textField('purchase_date', 'PO Date') !!}
            </div>
            <div class="three columns">
                {!! Form::selectField('payment_term', 'Payment Terms', $term_lists) !!}
            </div>
            <div class="three columns">
                {!! Form::numberField('delivery_terms', 'Delivery Terms') !!}
            </div>
            <div class="three columns">
                {!! Form::selectField('type', 'Type',[
                    'purchase_order'    =>  'Purchase Order',
                    'job_order'         =>  'Job Order',
                    'work_order'        =>  'Work Order',
                    'contract'          =>  'Contract',
                    'contract_and_po'   =>  'Contract And Purchase Order',
                ]) !!}
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                {!! Form::textareaField('remarks', 'Remarks', null, ['rows' => 3]) !!}
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                {!! Form::textareaField('action', 'Action', null, ['rows' => 3]) !!}
            </div>
        </div>

    </div>
</div>

<br>
<div class="row">
    <div class="six columns">
        <h3>Items <small>(bid amount: <i id='total_value'>0</i>/{{$bid_amount}})</small> </h3>
    </div>
    @if($data->mode_of_procurement != 'public_bidding')
    <div class="six columns align-right">
        <a class="button" id="upload-button"><i class="nc-icon-mini arrows-2_square-upload"></i></a>
    </div>
    @endif
</div>
<br>

@if($data->mode_of_procurement != 'public_bidding')
<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th width="30%">Description</th>
                    <th width="20%">Account Code</th>
                    <th width="5%">Qty</th>
                    <th width="5%">Unit</th>
                    <th width="12%">Unit Price</th>
                    <th width="13">Amount</th>
                    <th width="15%">Type</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@else

<div class="row">
    <div class="twelve columns">
        <table class='table'  id="item_table">
            <thead>
                <tr>
                    <th width="35%">Description</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Unit</th>
                    <th width="15%">Unit Price</th>
                    <th width="15">Amount</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td>
                        <input type='text' name='item_description[]' tabindex='-1'   class='input'/>
                    </td>
                    <td>
                        <input type='text' id='quantity_row' tabindex='-1' name='quantity[]'   class='input'/>
                    </td>
                    <td>
                        <input type='text' name='unit_measurement[]' tabindex='-1'   class='input'/>
                    </td>
                    <td>
                        <input type='text' name='unit_price[]' id='rows' class='input numeric unit_price'/>
                    </td>
                    <td>
                        <input type='text' id='total_amount' tabindex='-1' name='total_amount[]' class='input' readonly/>
                    </td>
                </tr> --}}
            </tbody>
        </table>
    </div>
</div>
@endif

{!!Form::close()!!}
@if($data->mode_of_procurement != 'public_bidding')
{!! Form::open(['route' => ['procurements.purchase-orders.import',$rfq_id], 'files' => true, 'id' => 'target']) !!}
    <input type="file" name="file" style="display: none" id="file-input">
{!!Form::close()!!}
@endif

@stop
@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">
    var count    =0;
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-purchase_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker

    // onchange rfq_number
    $( document ).ready(function() {
        var value   = "{{$rfq_id}}";
        var route   =   "/procurements/rfq/get-info"
        // alert('asds');
        $.ajax({
            type: 'GET',
            url: route+"/"+value,
            dataType: 'json',
            success: function (data) {

                removeAll()
                data.forEach(function(entry) {
                    count++;

                    addField(entry);
                });
            }
        });

    });
    // end onchange rfq_number

    function addField(entry)
    {
        var table=document.getElementById("item_table");
        var table_len=(table.rows.length) ;
        var newRow = "<tr id='row" + table_len + "'>";
            newRow += "<td id='desciption_row"+table_len+"'>";
            newRow += "<input type='text' name='item_description[]' tabindex='-1' readonly value='"+entry.item_description+"' class='input'/>"
            newRow += "</td>";

            newRow += "<td id='new_account_code_row"+table_len+"'>";

            newRow += "<select type='text' name='new_account_code[]'  class='select'>";

            newRow += "<option value='"+entry.new_account_code+"'>";
            newRow += entry.account_code;
            newRow += "</option>";
            newRow += "</select>";

            newRow += "<td >";
            newRow += "<input type='text' id='quantity_row"+table_len+"' tabindex='-1' name='quantity[]' readonly value='"+entry.quantity+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='unit_measurement_row"+table_len+"'>";
            newRow += "<input type='text' name='unit_measurement[]' tabindex='-1' readonly value='"+entry.unit_measurement+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='unit_price_row"+table_len+"'>";
            newRow += "<input type='text' name='unit_price[]' id='rows"+table_len+"' class='input numeric unit_price'/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input type='text' id='total_amount"+table_len+"' tabindex='-1' name='total_amount[]' value='' class='input total_amount' readonly/>";
            newRow += "</td>";
            newRow += "<td id='type_row"+table_len+"'>";
            newRow += "<select class='select' name='item_type[]' id='type"+table_len+"' tabindex='-1'><option value='purchase_order'>Purchase Order</option><option value='contract'>Contract</option></select>";
            newRow += "</td>";
            newRow += "</tr>";

        table.insertRow(table_len).outerHTML=newRow;
    }

    function delete_row(no)
    {
         document.getElementById("row"+no+"").outerHTML="";
    }


    function removeAll(){

        for(var x = 1; x<count; x++)
            removeElement(document.getElementById("row"+x))

        count = 1;

    }

    function removeElement(el) {
        el.parentNode.removeChild(el);
    }

    //
    // onchange unit_price
    $(document).on('change', '.unit_price', function(e){
        var id      =   $(this).attr('id');
        var price   =   $("#"+id).val();
        var splited =   id.split('rows');
        var quants  =   $("#quantity_row"+splited[1]).val();
        $("#total_amount").val(0)

        if(quants != "" && price != "")
        {
            price = price.replace(/,/g , "");
            total_amount    =   quants * price;
            var total_amount    = $("#total_amount"+splited[1]).val(total_amount);
        }

        var sum = 0;
        $('.total_amount').each(function(){
            var vals = parseFloat($(this).val())
            if(isNaN(vals)) {
              vals = 0
            }
            sum += vals;  // Or this.innerHTML, this.innerText
        });
        $('#total_value').text(sum)

    });

    // $('.unit_price').on('keypress', function(evt){
    //   if (evt.which < 48 || evt.which > 57)
    //       {
    //           evt.preventDefault();
    //       }
    // })

    $(document).on('click', '#upload-button', function(e){
        $("#file-input").click();
    });


    $(document).on('change', '#file-input', function(e){
        $('#target').submit();
    });



</script>
@stop