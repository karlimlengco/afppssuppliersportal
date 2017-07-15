@section('title')
Unit Purchase Request
@stop

@section('contents')

<div class="row">
    <div class="six columns utility utility--align-right" style="margin-bottom:0px" >
        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
    <div class="six columns utility"  style="margin-bottom:0px; text-align: left" >
        {!! Form::selectField('upr_number', '', $upr_list, ['id'=>'upr_id'])!!}
    </div>
</div>

<div class="data-panel" style="padding:10px; margin-bottom:10px">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label" style="font-weight:800; text-transform:capitalize">UPR No :</strong> {{$data->upr_number}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label" style="font-weight:800; text-transform:capitalize">Project Name :</strong> {{$data->project_name}} </li>
        </ul>
    </div>
</div>

    <?php
        $totalDays      =   0;
        $today          =   \Carbon\Carbon::now()->format('Y-m-d');
        $today          =   createCarbon('Y-m-d', $today);
        $upr_created    =   $data->date_prepared;
    ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Allowable Time</th>
                    <th>Day/s</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>UPR</td>
                    <td>{{ $data->date_prepared->format('d F Y')}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Create RFQ</td>
                    <td>
                        @if($data->rfq_created_at != null)
                        <?php $rfq_created_at  =  createCarbon('Y-m-d',$data->rfq_created_at); ?>
                            <a target="_blank" href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}">
                                {{ $rfq_created_at->format('d F Y') }}
                            </a>
                        @endif
                    </td>
                    <td >1</td>
                    <td>
                        @if($data->rfq_created_at != null)
                            {{ $data->rfq_days }}
                            <?php $totalDays +=  $data->rfq_days ; ?>

                            @if($data->rfq_days > 1)
                                <strong class="red">({{$data->rfq_days - 1}})</strong>
                            @endif

                        @else
                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $upr_created )}}
                        @endif
                    </td>
                    <td>{{$data->rfq_remarks}}</td>
                </tr>

                <tr>
                    <td>Close RFQ</td>
                    <td>
                        @if($data->rfq_completed_at != null)
                            <?php $rfq_completed_at = createCarbon('Y-m-d H:i:s',$data->rfq_completed_at)->format('d F Y'); ?>
                            <?php $rfq_completed_ats = createCarbon('Y-m-d H:i:s',$data->rfq_completed_at)->format('Y-m-d'); ?>
                            {{  $rfq_completed_at }}
                        @endif
                    </td>
                    <td>0</td>
                    <td>
                        @if(isset($rfq_completed_ats) && $rfq_completed_ats != null)
                            {{ $data->rfq_closed_days }}
                            <?php $totalDays +=  $data->rfq_closed_days ; ?>

                            @if($data->rfq_closed_days >= 1)
                                <strong class="red">({{$data->rfq_closed_days - 0}})</strong>
                            @endif

                        @else
                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $upr_created )}}
                        @endif

                    </td>
                    <td>{{$data->rfq_close_remarks}}</td>
                </tr>

                <tr>
                    <td>RFQ Create Invitation</td>
                    <td>
                        @if($data->ispq_transaction_date != null)
                            <?php $ispq_transaction_date = createCarbon('Y-m-d',$data->ispq_transaction_date); ?>
                        <a target="_blank" href="{{route('procurements.ispq.edit', $data->ispq_id)}}">{{$ispq_transaction_date->format('d F Y')}}</a>
                        @endif
                    </td>
                    <td>0</td>
                    <td>
                    @if(isset($rfq_completed_at))
                        @if($data->ispq_transaction_date != null)
                            {{ $data->ispq_days }}
                            <?php $totalDays +=  $data->ispq_days ; ?>


                            @if($data->ispq_days >= 1)
                                <strong class="red">({{$data->ispq_days - 0}})</strong>
                            @endif

                        @else
                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$data->rfq_completed_at) )}}
                        @endif
                    @endif

                    </td>
                    <td>{{$data->ispq_remarks}}</td>
                </tr>

                <tr>
                    <td>Philgeps posting</td>
                    <td>
                        @if($data->pp_completed_at != null)
                            <?php $pp_completed_at = createCarbon('Y-m-d',$data->pp_completed_at); ?>
                        <a target="_blank" href="{{route('procurements.philgeps-posting.show', $data->pp_id)}}">
                            {{$pp_completed_at->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>3</td>
                    <td>
                    @if(isset($ispq_transaction_date))
                        @if($data->pp_completed_at != null)
                            {{ $data->pp_days }}
                            <?php $totalDays +=  $data->pp_days ; ?>

                            @if($data->pp_days >= 1)
                                <strong class="red">({{$data->pp_days - 3}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ispq_transaction_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->pp_remarks}}</td>
                </tr>

                <tr>
                    <td>Canvassing</td>
                    <td>
                        @if($data->canvass_start_date != null)
                            <?php $canvass_start_date = createCarbon('Y-m-d',$data->canvass_start_date); ?>
                        <a target="_blank" href="{{route('procurements.canvassing.show', $data->canvass_id)}}">
                            {{$canvass_start_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($ispq_transaction_date))
                        @if($data->canvass_start_date != null)
                            {{ $data->canvass_days }}
                            <?php $totalDays +=  $data->canvass_days ; ?>

                            @if($data->canvass_days >= 1)
                                <strong class="red">({{$data->canvass_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ispq_transaction_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->canvass_remarks}}</td>
                </tr>
{{--
                <tr>
                    <td>Total</td>
                    <td>Working Days</td>
                    <td>{{$totalDays}}</td>
                </tr> --}}

            </tbody>
        </table>

@stop

@section('scripts')
<script type="text/javascript">

$(document).on('change', '#id-field-upr_number', function(e){
    var val = $("#id-field-upr_number").val();
    window.location.href = "/procurements/unit-purchase-requests/timelines/"+val;

});
</script>
@stop