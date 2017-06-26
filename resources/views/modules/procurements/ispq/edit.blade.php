@section('title')
Invitation to Submit Price Quotation
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')

{!! Form::model($data, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>


        <a target="_blank" href="{{route('procurements.ispq.print',$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>
        <a href="#" class="button topbar__utility__button--modal" ><i class="nc-icon-mini ui-1_trash"></i></a>
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

            <div class="row">


            </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        @if(count($data->quotations) != 0 )
            <table class="table">
                <thead>

                    <tr>
                        <th>RFQ Number</th>
                        <th>Description</th>
                        <th>ABC</th>
                        <th>Canvassing Date</th>
                        <th>Canvassing Time</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data->quotations as $quotation)
                    <tr>
                        <td>{{$quotation->rfq_number}}</td>
                        <td>{{$quotation->description}}</td>
                        <td>{{$quotation->total_amount}}</td>
                        <td>{{$quotation->canvassing_date}}</td>
                        <td>{{$quotation->canvassing_time}}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        @endif
    </div>
</div>


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
</script>
@stop