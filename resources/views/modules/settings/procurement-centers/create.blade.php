@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Procurement Centers</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open($modelConfig['store']) !!}
            {!! Form::textField('name', 'Name') !!}

            {!! Form::textField('address', 'Address') !!}

            <a class="button" href="{{route($indexRoute)}}">Back</a>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop
