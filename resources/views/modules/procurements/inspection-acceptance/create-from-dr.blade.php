@section('title')
Inspection And Acceptance Report
@stop

@section('contents')
{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute,$id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>


<div class="row">
    <div class="twelve columns">
            <div class="row">
                <div class="six columns">
                    {!! Form::textField('inspection_date', 'Inspection Date') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('nature_of_delivery', 'Nature Of Delivery') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('findings', 'Finding') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('recommendation', 'Recommendation') !!}
                </div>
            </div>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> {!! Form::text('a_invoice_number', null, ['class' => 'input', 'id' => 'invoice_number']) !!} </td>
                    <td> {!! Form::text('a_invoice_date', null, ['class' => 'input', 'id' => 'invoice_date']) !!} </td>
                    <td> <button type="button" class="button" id="add_item">add</button> </td>
                </tr>
            </tbody>
        </table>



    </div>
</div>
{!!Form::close()!!}

@stop
@section('scripts')
<script type="text/javascript">

    // datepicker
    var inspection_date = new Pikaday(
    {
        field: document.getElementById('id-field-inspection_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var invoice_date = new Pikaday(
    {
        field: document.getElementById('invoice_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker

    // add field


    // Add item button
    $(document).on('click', '#add_item', function(e){
        e.preventDefault();
        if( $("#invoice_number").val() != "" && $("#invoice_date").val() != "" )
        {
            addField();
        }
        else
        {
            alert('fill up all fields');
        }
    });
    // End Add item button

    function addField()
    {
        // vars
        var invoice_num       = $("#invoice_number").val();
        var invoice_date      = $("#invoice_date").val();
        // end vars

        var table=document.getElementById("item_table");
        var table_len=(table.rows.length)-1;

        var newRow = "<tr id='row" + table_len + "'>";
            newRow += "<td id='invoice_number"+table_len+"'>";
            newRow += "<input type='text' name='invoice_number[]' value='"+invoice_num+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='invoice_date"+table_len+"'>";
            newRow += "<input type='text' name='invoice_date[]' value='"+invoice_date+"' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input type='button' value='Delete' class='button delete' onclick='delete_row("+table_len+")'";
            newRow += "</td>";
            newRow += "</tr>";

        table.insertRow(table_len).outerHTML=newRow;

        $("#invoice_number").val("")
        $("#invoice_date").val("")
    }

    function delete_row(no)
    {
        console.log(no);
         document.getElementById("row"+no+"").outerHTML="";
    }
</script>
@stop