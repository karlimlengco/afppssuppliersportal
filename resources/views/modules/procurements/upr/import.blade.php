@section('title')
Unit Purchase Request
@stop

@section('contents')

{!! Form::open($modelConfig['importFile']) !!}
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>
<div class="row">
    <div class="twelve columns">
        {!! Form::fileField('file', 'File') !!}
    </div>
</div>

{!!Form::close()!!}

@stop

@section('scripts')
<script>

</script>
@stop
