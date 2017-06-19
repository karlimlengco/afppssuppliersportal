@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Chargeability</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open($modelConfig['store']) !!}
            {!! Form::textField('name', 'Name') !!}
            {!! Form::textareaField('description', 'Description') !!}

            <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop