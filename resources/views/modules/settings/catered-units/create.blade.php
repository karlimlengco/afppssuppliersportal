@section('title')
Units
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open($modelConfig['store']) !!}

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('pcco_id', 'PCCO', $center_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('short_code', 'Short Code') !!}
                </div>
            </div>

            {!! Form::textField('description', 'Description') !!}
            {!! Form::textareaField('coa_address', 'COA Address', null, ['rows' => 3]) !!}
            {!! Form::textareaField('coa_address_2', 'COA Address 2', null, ['rows' => 3]) !!}

            <a class="button" href="{{route($indexRoute)}}">Back</a>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop
