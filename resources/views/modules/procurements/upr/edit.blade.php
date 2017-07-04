@section('title')
Unit Purchase Request
@stop

@section('modal')
    @include('modules.partials.modals.delete')
{!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <a class="button" tooltip="Save" id="edit-button"><i class="nc-icon-mini ui-2_disk"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <div class="row">
            <div class="six columns">
                {!! Form::textField('project_name', 'Project Name') !!}
            </div>
            <div class="six columns">
                {!! Form::textField('date_prepared', 'Date Prepared') !!}
            </div>
        </div>

        <div class="row">
            <div class="six columns">
                {!! Form::textField('fund_validity', 'Fund Validity') !!}
            </div>
            <div class="six columns">
                {!! Form::selectField('terms_of_payment', 'Terms of Payment', $payment_terms) !!}
            </div>
        </div>

        <div class="row">
            <div class="six columns">
                {!! Form::textareaField('purpose', 'Purpose of Purchase', null, ['rows' => 3]) !!}
            </div>
            <div class="six columns">
                {!! Form::textareaField('other_infos', 'Other Essential Info', null, ['rows' => 3]) !!}
            </div>
        </div>

    </div>
</div>

{!!Form::close()!!}

@stop


@section('scripts')
<script>

    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_prepared'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop