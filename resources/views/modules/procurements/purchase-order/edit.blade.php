@section('title')
Purchase Order
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
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>
        <a class="button" tooltip="Delete" id="delete-button"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('type', 'Type',[
                      'purchase_order'    =>  'Purchase Order',
                      'job_order'         =>  'Job Order',
                      'work_order'        =>  'Work Order',
                      'contract'          =>  'Contract',
                      'contract_and_po'   =>  'Contract And Purchase Order',
                  ]) !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('payment_term', 'Payment Terms', $term_lists) !!}
                </div>
                <div class="four columns">
                    {!! Form::numberField('delivery_terms', 'Delivery Terms') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('po_number', 'PO Number') !!}
                </div>
            </div>


            <div class="row">
                <div class="four columns">
                    {!! Form::textField('purchase_date', 'Purchase Date') !!}
                </div>
                @if($data->funding_released_date != null)
                <div class="four columns">
                    {!! Form::textField('funding_released_date', 'MFO Obligation Released Date') !!}
                </div>
                @endif
                @if($data->funding_received_date != null)
                <div class="four columns">
                    {!! Form::textField('funding_received_date', 'MFO Obligation Received Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                @if($data->mfo_released_date != null)
                <div class="four columns">
                    {!! Form::textField('mfo_released_date', 'Issuance of CAF Released Date') !!}
                </div>
                @endif
                @if($data->mfo_received_date != null)
                <div class="four columns">
                    {!! Form::textField('mfo_received_date', 'Issuance of CAF Received Date') !!}
                </div>
                @endif
                @if($data->coa_approved_date != null)
                <div class="four columns">
                    {!! Form::textField('coa_approved_date', 'COA Approved Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                <div class="six columns">

                {{-- {!! Form::selectField('requestor_id', 'Requestor', $signatory_list) !!} --}}

                    <label class="label">Requestor</label>
                    {!! Form::select('requestor_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-requestor_id']) !!}
                </div>
                <div class="six columns">

                    <label class="label">Accounting</label>
                    {!! Form::select('accounting_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-accounting_id']) !!}
                    {{-- {!! Form::selectField('accounting_id', 'Accounting', $signatory_list) !!} --}}

                </div>
            </div>
            <div class="row">
                <div class="six columns">

                    <label class="label">Approver</label>
                    {!! Form::select('approver_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-approver_id']) !!}
                   {{-- {!! Form::selectField('approver_id', 'Approver', $signatory_list) !!} --}}
                </div>
                <div class="six columns">

                    <label class="label">COA Signatory</label>
                    {!! Form::select('coa_signatory',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-coa_signatory']) !!}
                    {{-- {!! Form::selectField('coa_signatory', 'COA Signatory', $signatory_list) !!} --}}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')

<script type="text/javascript">


    $requestor = $('#id-field-requestor_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $accounting = $('#id-field-accounting_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $approver = $('#id-field-approver_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $coa = $('#id-field-coa_signatory').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    coa           = $coa[0].selectize;
    approver      = $approver[0].selectize;
    accounting    = $accounting[0].selectize;
    requestor     = $requestor[0].selectize;

    $(document).on('submit', '#create-signatory-form', function(e){
        e.preventDefault();
        var inputs =  $("#create-signatory-form").serialize();

        $.ajax({
            type: "POST",
            url: '/api/signatories/store',
            data: inputs,
            success: function(result) {
                console.log(result);
                approver.addOption({value:result.id, text: result.name});
                coa.addOption({value:result.id, text: result.name});
                accounting.addOption({value:result.id, text: result.name});
                requestor.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });

    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })

    var coa_approved_date = new Pikaday(
    {
        field: document.getElementById('id-field-coa_approved_date'),
        firstDay: 1,
    });


    var mfo_received_date = new Pikaday(
    {
        field: document.getElementById('id-field-mfo_received_date'),
        firstDay: 1,
    });

    var mfo_released_date = new Pikaday(
    {
        field: document.getElementById('id-field-mfo_released_date'),
        firstDay: 1,
    });

    var purchase_date = new Pikaday(
    {
        field: document.getElementById('id-field-purchase_date'),
        firstDay: 1,
    });

    var funding_released_date = new Pikaday(
    {
        field: document.getElementById('id-field-funding_released_date'),
        firstDay: 1,
    });

    var funding_received_date = new Pikaday(
    {
        field: document.getElementById('id-field-funding_received_date'),
        firstDay: 1,
    });
    // end datepicker
</script>
@stop