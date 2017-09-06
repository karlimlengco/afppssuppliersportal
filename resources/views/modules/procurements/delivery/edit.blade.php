@section('title')
Notice Of Delivery
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

@section('contents')

{!! Form::model($data, $modelConfig['update']) !!}

    <div class="row">
        <div class="twelve columns align-right utility utility--align-right">

            <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

            <a href="{{route($showRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        </div>
    </div>
    <div class="row">
        <div class="twelve columns">

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('delivery_date', 'Delivery Date') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('delivery_number', 'Delivery Receipt') !!}
                </div>

            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('notes', 'Notes', null, ['rows'=>3]) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('delivery_remarks', 'Remarks', null, ['rows'=>3]) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('delivery_action', 'Action', null, ['rows'=>3]) !!}
                </div>
            </div>
            <div class="row">


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
                        <th>Received Qty</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->items as $item)
                        <input type="hidden" name="ids[]" value="{{$item->id}}">
                        <tr>
                            <td>{{$item->description}}</td>
                            <td>{{$item->quantity}}</td>
                            <td> {{Form::number('received_quantity[]', $item->quantity, ['class' => 'input', 'min' => 0])}} </td>
                            <td>{{$item->unit}}</td>
                            <td>{{formatPrice($item->price_unit)}}</td>
                            <td>{{formatPrice($item->total_amount)}}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

{!! Form::close() !!}

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    // datepicker

     var delivery_date = new Pikaday(
    {
        field: document.getElementById('id-field-delivery_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker
</script>
@stop