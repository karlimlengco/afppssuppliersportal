@section('title')
PhilGeps Posting
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
    @include('modules.partials.modals.dropzone')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="#" class=" topbar__utility__button--modal button__options__item">Add Attachment</a>
                <a href="{{route('procurements.unit-purchase-requests.show',$data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>
                <a href="{{route('procurements.philgeps-posting.logs', $data->id)}}" class=" button__options__item">View Logs</a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>


                {{-- <a href="{{route('procurements.blank-rfq.show',$data->rfq_id)}}" class=" button__options__item">Request For Quotation</a> --}}
            </div>
        </button>


        <a class="button" href="{{route($editRoute,$data->id)}}">
            <i class="nc-icon-mini design_pen-01"></i></a>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>

    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>

        Go to UPR
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>

    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR Number :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">PhilGeps Number :</strong> {{$data->philgeps_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{($data->status == 1) ? "Approved" : "Needs Re-Post"}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Transaction Date :</strong>  {{ createCarbon('Y-m-d',$data->transaction_date)->format('d M Y') }} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">PhilGeps Posting Date :</strong> {{ createCarbon('Y-m-d',$data->philgeps_posting)->format('d M Y') }}  </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Working Days :</strong> {{$data->days}} day(s) </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ Submition Deadline :</strong> {{ createCarbon('Y-m-d',$data->deadline_rfq)->format('d M Y') }}  </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Canvas Opening Time :</strong> {{$data->opening_time}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Remarks :</strong> {{$data->remarks}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Action :</strong> {{$data->action}} </li>
        </ul>
    </div>
</div>
<br>
<br>
@if(count($data->attachments) != 0)
<h3>Attachments</h3>
<br>
<div class="row">
    <div class="twelve columns">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>File Name</th>
                    <th>Uploaded By</th>
                    <th>Upload Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->attachments as  $attachment)
                <tr>
                    <td> <a href="{{route('procurements.philgeps-posting.attachments.download', $attachment->id)}}"> {{$attachment->name}} </a></td>
                    <td>{{$attachment->file_name}}</td>
                    <td>{{($attachment->users) ? $attachment->users->first_name ." ". $attachment->users->surname :""}}</td>
                    <td>{{$attachment->upload_date}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@stop

@section('scripts')
<script type="text/javascript">
</script>
@stop