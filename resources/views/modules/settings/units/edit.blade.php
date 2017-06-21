@section('title')
Units
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::model($data, $modelConfig['update']) !!}


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
