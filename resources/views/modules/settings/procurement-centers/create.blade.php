@section('title')
Procurement Centers
@stop

@section('contents')

{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="three columns">
            {!! Form::textField('name', 'Name') !!}
    </div>
    <div class="three columns">
            {!! Form::textField('short_code', 'Short Code') !!}
    </div>
    <div class="three columns">
            {!! Form::selectField('programs', 'Program', ['1' => '1', '2' => '2', '3' => '3', '4' => '4']) !!}
    </div>
    <div class="three columns">
            {!! Form::textField('telephone_number', 'Telephone #') !!}
    </div>
            {!! Form::textField('address', 'Address') !!}
</div>
{!!Form::close()!!}

@stop
