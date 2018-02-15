@section('title')
Units
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
    <div class="twelve columns">

        <div class="row">
            <div class="six columns">
                {!! Form::selectField('pcco_id', 'PCCO', $center_list) !!}
            </div>
            <div class="six columns">
                {!! Form::textField('short_code', 'Short Code') !!}
            </div>
        </div>

        {!! Form::textField('description', 'Description') !!}
        {!! Form::textareaField('coa_address', 'Address', null, ['rows' => 3]) !!}
        {!! Form::textareaField('coa_address_2', 'Address 2', null, ['rows' => 3]) !!}

    </div>
</div>

{!!Form::close()!!}
@stop
