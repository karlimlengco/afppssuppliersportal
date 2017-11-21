@section('title')
Unit Purchase Request
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
    @include('modules.partials.modals.delete')
    @include('modules.partials.create_signatory')
{!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <a class="button" tooltip="Save" id="edit-button"><i class="nc-icon-mini ui-2_disk"></i></a>

        @if(\Sentinel::getUser()->hasRole('Admin'))
        <a href="#" id="delete-button" class="button " tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
        @endif
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <div class="row">
            <div class="four columns">
                {!! Form::textField('project_name', 'Project Name') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('upr_number', 'UPR Number') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('place_of_delivery', 'Place Of Delivery') !!}
            </div>
        </div>

        <div class="row">
            <div class="four columns">
                {!! Form::textField('date_prepared', 'Date Prepared') !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('units', 'Units', $unit) !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('procurement_type', 'Procurement Program/Project', $procurement_types) !!}
            </div>
        </div>

        <div class="row">
            <div class="four columns">
                {!! Form::selectField('procurement_office', 'Procurement Center / Contracting Office', $procurement_center) !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('mode_of_procurement', 'Mode of Procurement', ['public_bidding' => 'Public Bidding']+ $procurement_modes) !!}
            </div>
            <div class="four columns">
                {!! Form::selectField('chargeability', 'Chargeability', $charges) !!}
            </div>
        </div>

        <div class="row">
{{--             <div class="four columns">
                {!! Form::selectField('new_account_code', 'New Account Code', $account_codes) !!}
            </div> --}}
            <div class="four columns">
                {!! Form::textField('fund_validity', 'Fund Validity') !!}
            </div>
            <div class="four columns">
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

        <h3><strong style="border-bottom:2px solid black">Signatories</strong></h3>


        <div class="row">
            <div class="four columns">
                <div class="form-group">
                    <label class="label">Request By</label>
                    {!! Form::select('requestor_id',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-requestor_id']) !!}
                </div>
            </div>
            <div class="four columns">

                <label class="label">Fund Certified Available</label>
                {!! Form::select('fund_signatory_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-fund_signatory_id']) !!}
                {{-- {!! Form::selectField('fund_signatory_id', 'Fund Certified Available', $signatory_list) !!} --}}
            </div>
            <div class="four columns">
                <label class="label">Approved By</label>
                {!! Form::select('approver_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-approver_id']) !!}
                {{-- {!! Form::selectField('approver_id', 'Approved By', $signatory_list) !!} --}}
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

    $('#delete-button').click(function(e){
        e.preventDefault();
        $('#delete-modal').addClass('is-visible');
    })

    $requestor = $('#id-field-requestor_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $funder = $('#id-field-fund_signatory_id').selectize({
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

    requestor  = $requestor[0].selectize;
    funder  = $funder[0].selectize;
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
                requestor.addOption({value:result.id, text: result.name});
                funder.addOption({value:result.id, text: result.name});
                approver.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_prepared'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop