@section('title')
Vouchers
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

        <button type="button" class="button"  id="edit-button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <a href="#" class="button topbar__utility__button--modal" ><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                @if($data->payment_release_date)
                <div class="four columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('payment_release_date', 'Payment Release Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('payment_received_date', 'Payment Received Date') !!}
                </div>
                @else
                    <div class="six columns">
                        {!! Form::textField('transaction_date', 'Transaction Date') !!}
                    </div>
                @endif
            </div>

            <div class="row">
                @if($data->preaudit_date)
                <div class="six columns">
                    {!! Form::textField('preaudit_date', 'Pre Audit Date') !!}
                </div>
                @endif
                @if($data->certify_date)
                <div class="six columns">
                    {!! Form::textField('certify_date', 'Certify Date') !!}
                </div>
                @endif
            </div>
            <div class="row">
                @if($data->journal_entry_date)
                <div class="six columns">
                    {!! Form::textField('journal_entry_date', 'Journal Entry Date') !!}
                </div>
                @endif
                @if($data->approval_date)
                <div class="six columns">
                    {!! Form::textField('approval_date', 'Approval Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('amount', 'Amount') !!}
                </div>
                <div class="four columns">
                    {!! Form::numberField('final_tax', 'Final Tax') !!}
                </div>
                <div class="four columns">
                    {!! Form::numberField('expanded_witholding_tax', 'Expanded Witholding Tax') !!}
                </div>
            </div>
            <div class="row">
                <div class="four columns">
                    {!! Form::textField('final_tax_amount', 'Final Tax Amount') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('ewt_amount', 'EWT Amount') !!}
                </div>
            </div>


            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('suppliers_address', 'Suppliers Address', null, ['rows'=>3]) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('bir_address', 'BIR Address', null, ['rows'=>3]) !!}
                </div>
            </div>


            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('certified_by', 'Certified By', $signatory_list) !!} --}}

                    <label class="label">Certified By</label>
                    {!! Form::select('certified_by',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-certified_by']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('approver_id', 'Approved By', $signatory_list) !!} --}}
                    <label class="label">Approved By</label>
                    {!! Form::select('approver_id',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-approver_id']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('receiver_id', 'Conforme', $signatory_list) !!} --}}
                    <label class="label">Conforme</label>
                    {!! Form::select('receiver_id',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-receiver_id']) !!}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')
<script type="text/javascript">

    $(document).on('change', '#id-field-final_tax', function(e){
        var final_tax  = $("#id-field-final_tax").val();
        var total_amount  = $("#id-field-amount").val();
        var ft_amount   = total_amount * (final_tax / 100);
        $("#id-field-final_tax_amount").val(ft_amount.toFixed(2));
    });

    $(document).on('change', '#id-field-expanded_witholding_tax', function(e){
        var expanded_witholding_tax  = $("#id-field-expanded_witholding_tax").val();
        var total_amount  = $("#id-field-amount").val();
        var ewt_amount   = total_amount * (expanded_witholding_tax / 100);
        $("#id-field-ewt_amount").val(ewt_amount.toFixed(2));
    });


    $certified_by = $('#id-field-certified_by').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $approver_id = $('#id-field-approver_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $receiver_id = $('#id-field-receiver_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    certified_by  = $certified_by[0].selectize;
    approver_id  = $approver_id[0].selectize;
    receiver_id  = $receiver_id[0].selectize;

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
                certified_by.addOption({value:result.id, text: result.name});
                approver_id.addOption({value:result.id, text: result.name});
                receiver_id.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });

    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })

    var transaction_date = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
    });

    var payment_release_date = new Pikaday(
    {
        field: document.getElementById('id-field-payment_release_date'),
        firstDay: 1,
    });

    var payment_received_date = new Pikaday(
    {
        field: document.getElementById('id-field-payment_received_date'),
        firstDay: 1,
    });

    var preaudit_date = new Pikaday(
    {
        field: document.getElementById('id-field-preaudit_date'),
        firstDay: 1,
    });

    var certify_date = new Pikaday(
    {
        field: document.getElementById('id-field-certify_date'),
        firstDay: 1,
    });

    var journal_entry_date = new Pikaday(
    {
        field: document.getElementById('id-field-journal_entry_date'),
        firstDay: 1,
    });

    var approval_date = new Pikaday(
    {
        field: document.getElementById('id-field-approval_date'),
        firstDay: 1,
    });
</script>
@stop