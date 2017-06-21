@section('title')
Invitation to Submit Price Quotation
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop
@section('contents')
{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
    <div class="six columns align-right">
        <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
        <button type="submit" class="button">Save</button>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <div class="row">
            <div class="six columns">
                {!! Form::textField('transaction_date', 'Transaction Date') !!}
            </div>
            <div class="six columns">
                {!! Form::selectField('signatory_id', 'Signatories', $signatory_lists) !!}
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                {!! Form::textareaField('venue', 'Venue', null, ['rows'=>3]) !!}
            </div>
        </div>

    </div>
</div>
<br>
<br>
<br>
<div class="row">
    <div class="twelve columns">

        <div class="row">
            <div class="six columns">
                <h3>Request For Quotation</h3>
            </div>
            <div class="five columns">
                {!! Form::select('rfq_id', [''=> 'Select One']+$rfq_list, null, ['class'=>'  select', 'id' => 'rfq_id']) !!}
            </div>
            <div class="one columns">
                <button class="button" type='button' id="add_rfq">Add</button>
            </div>
        </div>

        <table class="table" id="item_table">
            <thead>
                <tr>
                    <th>RFQ Number</th>
                    <th>Item Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{!!Form::close()!!}

@stop
@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    // datepicker
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker

    // // Add item button
    $(document).on('click', '#add_rfq', function(e){
        e.preventDefault();
        addField()
    });

    //
    function addField()
    {
        // vars
        var rfq_id       = $("#rfq_id option:selected").val();
        var rfq_id_text = $("#rfq_id option:selected").text();
        // end vars

        var table=document.getElementById("item_table");
        var table_len=(table.rows.length)-1;

        var newRow = "<tr id='row" + table_len + "'>";
            newRow += "<td id='desciption_row"+table_len+"'>";
            newRow += "<input type='hidden' name='items[]' value='"+rfq_id+"' class='input'/>"+rfq_id_text;
            newRow += "</td>";
            newRow += "<td id='name_row"+table_len+"'>";
            newRow += "<input type='text' name='description[]' value='' class='input'/>";
            newRow += "</td>";
            newRow += "<td id='total_amount_row"+table_len+"'>";
            newRow += "<input type='button' value='Delete' class='button delete' onclick='delete_row("+table_len+")'";
            newRow += "</td>";
            newRow += "</tr>";

        table.insertRow(table_len).outerHTML=newRow;
        $("#rfq_id").val("")
    }

    function delete_row(no)
    {
        console.log(no);
         document.getElementById("row"+no+"").outerHTML="";
    }
    // End Add item button
</script>
@stop