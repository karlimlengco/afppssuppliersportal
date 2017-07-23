@section('title')
Units
@stop

@section('modal')
    @include('modules.partials.modals.delete')
    @include('modules.partials.modals.unit_attachments')
@stop

@section('contents')

{!! Form::model($data, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <a href="#" id="attachment-button" class="button" tooltip="Attachments"><i class="nc-icon-mini ui-1_attach-86"></i> </a>

        <button type="submit" class="button"  tooltip="Save">
        <i class="nc-icon-mini ui-2_disk"></i>
        </button>

        <a href="" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

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

    </div>
</div>

{!! Form::close() !!}

@stop

@section('scripts')
<script type="text/javascript">

    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#unit-attachment-modal').addClass('is-visible');
    })
</script>
@stop
