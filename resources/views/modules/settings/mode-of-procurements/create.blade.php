@section('title')
Mode of Procurement
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
        {!! Form::textField('name', 'Name') !!}

        {!! Form::textField('description', 'Description') !!}

        <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
        <button type="submit" class="button">Save</button>
    </div>
</div>

{!!Form::close()!!}
@stop
