@section('title')
Suppliers
@stop

@section('modal')
    @include('modules.partials.modals.supplier_attachments')
    <div class="modal" id="delete-modal">
        <div class="modal__dialogue modal__dialogue--round-corner">
            <form method="DELETE" id="delete-form" action="" accept-charset="UTF-8">
                <button type="button" class="modal__close-button">
                    <i class="nc-icon-outline ui-1_simple-remove"></i>
                </button>

                <div class="moda__dialogue__head">
                    <h1 class="modal__title">Confirm Delete</h1>
                </div>

                <div class="modal__dialogue__body">

                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input name="_method" type="hidden" value="DELETE">
                    Are you sure you want to <strong>delete</strong> this record?
                </div>

                <div class="modal__dialogue__foot">
                    <button class="button">Proceed</button>
                </div>

            </form>
        </div>
    </div>
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <a href="#" id="attachment-button" class="button" tooltip="Attachments"><i class="nc-icon-mini ui-1_attach-86"></i> </a>

        <a   href="{{route( 'settings.suppliers.edit',[$data->id])}}"  class="button "  tooltip="Edit">
          <i class="nc-icon-mini design_pen-01"></i>
        </a>

    </div>
</div>


<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Name :</strong>
              {{$data->name}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Owner :</strong>
              {{$data->owner}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Tin :</strong>
              {{$data->tin}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Address :</strong>
              {{$data->address}}
            </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Bank :</strong>
              {{$data->bank_id or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Branch :</strong>
              {{$data->branch or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Account # :</strong>
              {{$data->account_number or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Account Type :</strong>
              {{$data->account_type or "&mdash;"}}
            </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Cell # :</strong>
              {{$data->cell_1 or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Email Address :</strong>
              {{$data->email_1 or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Phone # :</strong>
              {{$data->phone_1 or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Fax :</strong>
              {{$data->fax_1 or "&mdash;"}}
            </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label">Line of Business :</strong>
              {{$data->line_of_business}}
            </li>
        </ul>
    </div>
</div>
<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label"> Secondary Cell # :</strong>
              {{$data->cell_2 or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label"> Secondary Email Address :</strong>
              {{$data->email_2 or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label"> Secondary Phone # :</strong>
              {{$data->phone_2 or "&mdash;"}}
            </li>
            <li class="data-panel__list__item">
              <strong class="data-panel__list__item__label"> Secondary Fax :</strong>
              {{$data->fax_2 or "&mdash;"}}
            </li>
        </ul>
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
                <th></th>
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
                <td>

                <a href="#" data-id="{{$attachment->id}}" class="button delete-button" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

@stop

@section('scripts')

<script type="text/javascript">


    $('.delete-button').click(function(e){
        e.preventDefault();
        var val = $(this).data('id');

        $('#delete-form').attr('action', '/settings/suppliers/attachments/delete/'+val);
        $('#delete-modal').addClass('is-visible');
    })

    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#unit-attachment-modal').addClass('is-visible');
    })
    // end datepicker
</script>
@stop