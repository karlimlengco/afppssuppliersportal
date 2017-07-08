@section('title')
Chargeability
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
        {!! Form::textField('title', 'Title') !!}
        {!! Form::textareaField('message', 'Message') !!}

        <div class="row">
            <six class=" six columns">
                {!! Form::textField('post_at', 'Post Date') !!}
            </six>
            <six class=" six columns">
                {!! Form::textField('expire_at', 'Expiration Date') !!}
            </six>
        </div>

    </div>
</div>

{!!Form::close()!!}
@stop


@section('scripts')
<script>

    var post_at = new Pikaday(
    {
        field: document.getElementById('id-field-post_at'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var expire_at = new Pikaday(
    {
        field: document.getElementById('id-field-expire_at'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

</script>
@stop
