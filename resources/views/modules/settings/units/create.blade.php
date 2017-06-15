@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Units</h3>
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
                    {!! Form::textField('name', 'Name') !!}
                </div>
            </div>

            {!! Form::textField('description', 'Description') !!}
            {!! Form::textareaField('coa_address', 'COA Address') !!}

            <a class="button" href="{{route($indexRoute)}}">Back</a>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop
