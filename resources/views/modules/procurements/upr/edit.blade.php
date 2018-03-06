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
    @include('modules.partials.edit_line_item_modal')


    <div class="modal" id="import-item-modal">
        <div class="modal__dialogue modal__dialogue--round-corner">
             <form method="POST" enctype="multipart/form-data" action="{{ route('procurements.upr-items.import', $data->id) }}" accept-charset="UTF-8">
                <button type="button" class="modal__close-button">
                    <i class="nc-icon-outline ui-1_simple-remove"></i>
                </button>

                <div class="moda__dialogue__head">
                    <h1 class="modal__title">Import New Item</h1>
                </div>

                <div class="modal__dialogue__body">
                    {!! Form::selectField('code', 'Account Code', $accounts) !!}
                    <input type="file" name="file" class="custom-file-input" >
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input name="_method" type="hidden" value="POST">
                </div>

                <div class="modal__dialogue__foot">
                    <button class="button">Proceed</button>
                </div>

            </form>
        </div>
    </div>

    <div class="modal" id="add-item-modal">
        <div class="modal__dialogue modal__dialogue--round-corner">
             <form method="POST" action="{{route('procurements.upr-items.store', $data->id)}}" accept-charset="UTF-8">
                <button type="button" class="modal__close-button">
                    <i class="nc-icon-outline ui-1_simple-remove"></i>
                </button>

                <div class="moda__dialogue__head">
                    <h1 class="modal__title">Add New Item</h1>
                </div>

                <div class="modal__dialogue__body">
                    {!! Form::selectField('new_account_code', 'Account Code', $accounts) !!}
                    {!! Form::textField('item_description', 'Item Description', null, ['required']) !!}
                    <div class="row">
                      <div class="six columns">
                        {!! Form::textField('quantity', 'Quantity', null, ['required']) !!}
                      </div>
                      <div class="six columns">
                        {!! Form::textField('unit_measurement', 'Unit of Measurement', null, ['required']) !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="six columns">
                        {!! Form::textField('unit_price', 'Unit Price', null, ['required']) !!}
                      </div>
                    </div>


                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input name="_method" type="hidden" value="POST">
                </div>

                <div class="modal__dialogue__foot">
                    <button class="button">Proceed</button>
                </div>

            </form>
        </div>
    </div>

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
                {!! Form::textField('project_name', 'Project Name / Activity') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('upr_number', 'UPR Number') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('place_of_delivery', 'Place Of Delivery') !!}
            </div>
        </div>

        <div class="row">
            <div class="three columns">
                {!! Form::textField('date_processed', 'Date Process') !!}
            </div>
            <div class="three columns">
                {!! Form::textField('date_prepared', 'Date Prepared') !!}
            </div>
            <div class="three columns">
                {!! Form::selectField('units', 'Units', $unit) !!}
            </div>
            <div class="three columns">
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
                {{-- {!! Form::selectField('chargeability', 'Chargeability', $charges) !!} --}}
                    {!! Form::textField('chargeability', 'Chargeability') !!}
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

        <h3><strong style="border-bottom:2px solid black">Items</strong></h3>

        @if($data->status == 'upr_processing')
        <button id="add-item-button" class="button">ADD ITEM</button>

        <button type="button" id="import-item" class="button pull-right" tooltip="Import"><i class="nc-icon-mini arrows-1_cloud-upload-96"></i></button>

        @endif
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>AccountCode</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->items as $item)
                    <tr>
                        <td>{{$item->item_description}}</td>
                        <td>{{($item->accounts) ? "[". $item->accounts->old_account_code ."]".$item->accounts->new_account_code ." - ". $item->accounts->name : ""}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->unit_measurement}}</td>
                        <td>{{$item->unit_price}}</td>
                        <td>{{formatPrice($item->total_amount)}}</td>
                        <td>

                        @if($data->status == 'upr_processing')

                          <a  tooltip="edit" href="#" data-id="{{$item->id}}" data-quantity="{{$item->quantity}}" data-account_code="{{$item->new_account_code}}" data-unit_measurement="{{$item->unit_measurement}}" data-price="{{$item->unit_price}}" data-description="{{$item->item_description}}" class="edit-price-button" tooltip="Edit">
                              <i class="nc-icon-mini design_pen-01"></i>
                          </a>

                          <a tooltip="remove" href="{{route('procurements.upr-items.destroy', $item->id)}}"><i class="nc-icon-mini ui-1_trash"></i></a>

                        @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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

    $('#import-item').click(function(e){
        e.preventDefault();
        $('#import-item-modal').addClass('is-visible');
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

    $('#add-item-button').click(function(e){
        e.preventDefault();
        $('#add-item-modal').addClass('is-visible');
    })

    $funder = $('#id-field-fund_signatory_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });


    $('#close-line-item-button').click(function(e){
        $('#edit-line-item-modal').removeClass('is-visible');
    })

    $approver = $('#id-field-approver_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $('.edit-price-button').click(function(e){
        e.preventDefault();
        var id = $(this).data('id')
        var description = $(this).data('description')
        var price_unit = $(this).data('price')
        var quantity = $(this).data('quantity')
        var unit_measurement = $(this).data('unit_measurement')
        $('#update-line-item-form').attr('action', '/line-item/update/'+id);
        $(".id-field-quantity").val(quantity)
        $(".id-field-unit_price").val(price_unit)
        // $(".id-field-new_account_code").val($(this).data('account_code'))
        var $select = $('select').selectize();  // This initializes the selectize control
        var selectize = $select[0].selectize;
        selectize.setValue($(this).data('account_code'), false);
        $(".id-field-unit_measurement").val(unit_measurement)
        $("#title_description").text(description)
        $('#edit-line-item-modal').addClass('is-visible');
    })

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

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_processed'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop