@section('title')
Suppliers
@stop

@section('modal')
    @include('modules.partials.modals.delete')
    @include('modules.partials.modals.accept')
    @include('modules.partials.modals.blocked')
    @include('modules.partials.modals.supplier_attachments')
@stop

@section('contents')

{!! Form::model($data, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        @if($data->status == 'draft')
            <button type="button" class="button button--options-trigger" tooltip="Options">
                <i class="nc-icon-mini ui-2_menu-dots"></i>
                <div class="button__options">
                    <a href="#" id="accept-button" class="button__options__item ">Accept</a>
                </div>
            </button>
        @endif

        <a href="#" id="attachment-button" class="button" tooltip="Attachments"><i class="nc-icon-mini ui-1_attach-86"></i> </a>

        <button type="submit" class="button "  tooltip="Save">
        <i class="nc-icon-mini ui-2_disk"></i>
        </button>

        <a href="#" class="button" id="delete-button" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>

        @if($data->is_blocked == 0)
            <a href="#" class="button" id="blocked-button" tooltip="Blocked"><i class="nc-icon-mini ui-e_round-e-alert"></i></a>
        @else
            <a href="{{route('settings.suppliers.un-blocked', $data->id)}}" class="button" tooltip="Un Blocked"><i class="nc-icon-mini ui-1_check-circle-08"></i></a>
        @endif
    </div>
</div>


@if($data->is_blocked == 1)
    <div class="data-panel">
        <div class="data-panel__section">
            <ul class="data-panel__list">
                <li class="data-panel__list__item">
                    <strong class="data-panel__list__item__label">
                    Date Blocked :
                    </strong>
                    {{$data->date_blocked}}
                </li>
                <li class="data-panel__list__item">
                    <strong class="data-panel__list__item__label">
                    Remarks :
                    </strong>
                    {{$data->blocked_remarks}}
                </li>
            </ul>
        </div>
    </div>
@endif

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('name', 'Name') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('owner', 'Owner') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('tin', 'Tin') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('address', 'Address', null,['rows'=>2]) !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::selectField('bank_id', 'Bank', $bank_lists) !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('branch', 'Branch') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('account_number', 'Account Number') !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('account_type', 'Account Type') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('cell_1', 'Cell #') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('email_1', 'Email') !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('phone_1', 'Phone #') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('fax_1', 'Fax') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('cell_2', 'Secondary Cell #') !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('email_2', 'Secondary Email') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('phone_2', 'Secondary Phone #') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('fax_2', 'Secondary Fax') !!}
                </div>
            </div>

            @if(count($data->attachments) != 0)

                <table class="table">
                     <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Uploaded By</th>
                            <th>Ref Number</th>
                            <th>Place</th>
                            <th>Issued Date</th>
                            <th>Validity Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->attachments as  $attachment)
                        <tr>
                            <td> <a target="_blank" href="{{route('settings.suppliers.attachments.download', $attachment->id)}}"> {{$attachment->name}} </a></td>
                            <td style="text-transform:uppercase">{{$attachment->type}}</td>
                            <td>{{($attachment->users) ? $attachment->users->first_name ." ". $attachment->users->surname :""}}</td>
                            <td>{{$attachment->ref_number}}</td>
                            <td>{{$attachment->place}}</td>
                            <td>{{$attachment->issued_date}}</td>
                            <td>{{$attachment->validity_date}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
    </div>
</div>

{!! Form::close() !!}
@stop

@section('scripts')

<script type="text/javascript">

    $('#delete-button').click(function(e){
        e.preventDefault();
        $('#delete-modal').addClass('is-visible');
    })

    $('#blocked-button').click(function(e){
        e.preventDefault();
        $('#blocked-modal').addClass('is-visible');
    })

    $('#accept-button').click(function(e){
        e.preventDefault();
        $('#accept-modal').addClass('is-visible');
    })
    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#unit-attachment-modal').addClass('is-visible');
    })
    // end datepicker

    $(document).on('click', '#accept-button', function(e){
        var supplierId  = "{{$data->id}}";
        var form = document.getElementById('accept-form').action;
        document.getElementById('accept-form').action = "/settings/suppliers/accepts/"+supplierId;
    });
    var date_blocked = new Pikaday(
    {
        field: document.getElementById('id-field-date_blocked'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop