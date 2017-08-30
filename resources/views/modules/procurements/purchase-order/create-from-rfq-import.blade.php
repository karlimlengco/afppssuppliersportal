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
            <div class="four columns">
                {!! Form::textField('purchase_date', 'PO Date') !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('payment_term', 'Payment Terms', $term_lists) !!}
            </div>
            <div class="four columns">
                {!! Form::numberField('delivery_terms', 'Delivery Terms') !!}
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
        <h3>Items</h3>
    </div>
    <div class="six columns align-right">
        <a class="button" id="upload-button"><i class="nc-icon-mini arrows-2_square-upload"></i></a>
    </div>
</div>
<br>

<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th width="35%">Description</th>
                    <th width="5%">Qty</th>
                    <th width="10%">Unit</th>
                    <th width="15%">Unit Price</th>
                    <th width="20%">Amount</th>
                    <th width="15%" >Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rfq_model as $item)
                    <tr>
                        <td>
                        <input
                            type="text"
                            name='item_description[]'
                            tabindex='-1'
                            readonly
                            class='input'
                            value="{{$item->item_description}}">
                        </td>
                        <td>
                        <input
                            type='text'
                            id='quantity_row'
                            tabindex='-1'
                            name='quantity[]'
                            readonly
                            value="{{$item->quantity}}"
                            class='input'/>
                        </td>
                        <td>
                        <input
                            type='text'
                            name='unit_measurement[]'
                            tabindex='-1'
                            readonly
                            value="{{$item->unit_measurement}}"
                            class='input'/>
                        </td>
                        <td>
                        <input
                            type='number'
                            name='unit_price[]'
                            id='rows'
                            value="{{$item->unit_price}}"
                            class='input numeric unit_price'/>
                        </td>
                        <td>
                        <input
                            type='text'
                            id='total_amount'
                            tabindex='-1'
                            name='total_amount[]'
                            value="{{$item->total_amount}}"
                            class='input'
                            readonly/>

                        </td>
                        <td>
                            <select class='select' name='item_type[]' id='type"+table_len+"' tabindex='-1'><option value='purchase_order'>Purchase Order</option><option value='contract'>Contract</option></select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{!!Form::close()!!}
{!! Form::open(['route' => ['procurements.purchase-orders.import',$rfq_id], 'files' => true, 'id' => 'target']) !!}
    <input type="file" name="file" style="display: none" id="file-input">
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
    // onchange unit_price

    $('.unit_price').on('keypress', function(e){
      if (evt.which < 48 || evt.which > 57)
          {
              evt.preventDefault();
          }
    })

    $(document).on('click', '#upload-button', function(e){
        $("#file-input").click();
    });


    $(document).on('change', '#file-input', function(e){
        $('#target').submit();
    });



</script>
@stop