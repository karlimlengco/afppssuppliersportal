@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Signatories</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open($modelConfig['store']) !!}

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('name', 'Name') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('designation', 'Designation') !!}
                </div>
            </div>

            <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop
