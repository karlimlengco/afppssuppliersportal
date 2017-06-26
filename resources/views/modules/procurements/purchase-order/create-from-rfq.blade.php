@section('title')
Purchase Order
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
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
            <div class="six columns">
                {!! Form::textField('purchase_date', 'Purchase Date') !!}
            </div>
            <div class="six columns">
                {!! Form::selectField('payment_term', 'Payment Terms', $term_lists) !!}
            </div>
        </div>

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
            </tbody>
        </table>
    </div>
</div>

{!!Form::close()!!}
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
            newRow += "<input type='text' name='item_description[]' readonly value='"+entry.item_description+"' class='input'/>"
            newRow += "</td>";
            newRow += "<td >";
            newRow += "<input type='text' id='quantity_row"+table_len+"' name='quantity[]' readonly value='"+entry.quantity+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='unit_measurement_row"+table_len+"'>";
            newRow += "<input type='text' name='unit_measurement[]' readonly value='"+entry.unit_measurement+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='unit_price_row"+table_len+"'>";
            newRow += "<input type='text' name='unit_price[]' id='rows"+table_len+"' class='input unit_price'/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input type='text' id='total_amount"+table_len+"' name='total_amount[]' value='' class='input' readonly/>";
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
            total_amount    =   quants * price;
            console.log(total_amount);
            var total_amount    = $("#total_amount"+splited[1]).val(total_amount);
        }

    });


</script>
@stop