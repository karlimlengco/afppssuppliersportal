@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="six columns align-left">
        <h3>Mode of Procurement</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::model($data, $modelConfig['update']) !!}

            {!! Form::textField('name', 'Name') !!}

            {!! Form::textField('description', 'Description') !!}

            <div class="row">

                <div class="six columns">
                    <a class="button" href="{{route($indexRoute)}}"> Back </a>
                    <button class="button">Save</button>
                </div>

                <div class="six columns align-right">
                    <button class="button topbar__utility__button--modal" >Delete</button>
                </div>

            </div>
        {!! Form::close() !!}
    </div>
</div>


@stop