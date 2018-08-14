@section('title')
Request For Quotation
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop

@section('modal')
    @include('modules.partials.create_signatory')
    @include('modules.partials.modals.delete')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="button" id="edit-button" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="#" id="delete-button" class="button" ><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('rfq_number', "RFQ Number") !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('upr_id', 'UPR Number', $upr_list, null, ['disabled' => 'disabled']) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {{-- {!! Form::selectField('chief', 'Chief', $signatory_list) !!} --}}

                    <label class="label">Chief</label>
                    {!! Form::select('chief',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-chief']) !!}
                </div>
                @if($data->completed_at != null)
                <div class="six columns">
                    {!! Form::textField('completed_at', 'Completed Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('close_remarks', 'Remarks', null, ['rows' => '3']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('close_action', 'Action', null, ['rows' => 3]) !!}
                </div>
            </div>
    </div>
</div>

{!! Form::close() !!}

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })


    $('#delete-button').click(function(e){
        e.preventDefault();
        $('#delete-modal').addClass('is-visible');
    })

    // datepicker
    // pickmeup('#id-field-transaction_date', {
    //     format  : 'Y-m-d',
    //     default_date: false
    // });

    // pickmeup('#id-field-deadline', {
    //     format  : 'Y-m-d',
    //     default_date: false
    // });

    $approver = $('#id-field-chief').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    approver  = $approver[0].selectize;

    $(document).on('submit', '#create-signatory-form', function(e){
        e.preventDefault();
        var inputs =  $("#create-signatory-form").serialize();

        console.log(inputs);
        $.ajax({
            type: "POST",
            url: '/api/signatories/store',
            data: inputs,
            success: function(result) {
                console.log(result);
                approver.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var completed_at = new Pikaday(
    {
        field: document.getElementById('id-field-completed_at'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker
</script>
@stop