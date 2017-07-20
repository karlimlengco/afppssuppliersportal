@section('title')
Bid Proponents
@stop

@section('contents')

{!! Form::model($proponent, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('biddings.bid-openings.show',$id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="four columns">
        {!! Form::textField('bid_amount', 'Bid Amount')!!}
    </div>

    <div class="four columns">
        {!! Form::booleanField('is_lcb', 'LCB')!!}
    </div>

    <div class="four columns">
        {!! Form::booleanField('is_scb', 'SCB')!!}
    </div>
</div>

{!!Form::close()!!}

@stop

@section('scripts')
@stop