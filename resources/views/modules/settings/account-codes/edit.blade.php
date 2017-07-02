@section('title')
Account Codes
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')

{!! Form::model($data, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="submit" class="button topbar__utility__button--modal"  tooltip="Save">
        <i class="nc-icon-mini ui-2_disk"></i>
        </button>

        <a href="" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::textField('name', 'Name') !!}
        {!! Form::textField('old_account_code', 'Old Account Code') !!}
        {!! Form::textField('new_account_code', 'Account Code') !!}
    </div>
</div>

{!! Form::close() !!}
@stop
