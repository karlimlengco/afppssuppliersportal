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

                            @if($data->pp_days > 3)
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

                            @if($data->canvass_days > 1)
                                <strong class="red">({{$data->canvass_days - 1}})</strong>
                            @endif

                        @else
                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ispq_transaction_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->canvass_remarks}}</td>
                </tr>

                <tr>
                    <td>Prepare Notice Of Award</td>
                    <td>
                        @if($data->noa_award_date != null)
                            <?php $noa_award_date = createCarbon('Y-m-d H:i:s',$data->noa_award_date)->format('Y-m-d'); ?>
                            <?php $noa_award_date = createCarbon('Y-m-d',$noa_award_date); ?>
                        <a target="_blank" href="{{route('procurements.noa.show', $data->noa_id)}}">
                            {{$noa_award_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if(isset($canvass_start_date))
                        @if($data->noa_award_date)
                            {{ $data->noa_days }}
                            <?php $totalDays +=  $data->noa_days ; ?>

                            @if($data->noa_days > 2)
                                <strong class="red">({{$data->noa_days - 2}})</strong>
                            @endif


                        @else
                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $canvass_start_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->noa_remarks}}</td>
                </tr>

                <tr>
                    <td>Approved Notice Of Award</td>
                    <td>
                        @if($data->noa_approved_date != null)
                        <?php $noa_approved_date = createCarbon('Y-m-d',$data->noa_approved_date); ?>
                        <a target="_blank" href="{{route('procurements.noa.show', $data->noa_id)}}">
                            {{$noa_approved_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($noa_award_date))
                        @if($data->noa_approved_date != null)
                             {{ $data->noa_approved_days }}
                            <?php $totalDays +=  $data->noa_approved_days ; ?>

                            @if($data->noa_approved_days > 1)
                                <strong class="red">({{$data->noa_approved_days - 1}})</strong>
                            @endif


                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_award_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->noa_approved_remarks}}</td>
                </tr>

                <tr>
                    <td>Received Notice Of Award</td>
                    <td>
                        @if($data->noa_award_accepted_date != null)
                        <?php $noa_award_accepted_date = createCarbon('Y-m-d',$data->noa_award_accepted_date); ?>
                        <a target="_blank" href="{{route('procurements.noa.show', $data->noa_id)}}">
                            {{$noa_award_accepted_date->format('d F Y')}}
                        </a>
                        @endif
                    <td>1</td>

                    <td>
                    @if(isset($noa_approved_date))
                        @if($data->noa_award_accepted_date != null)
                             {{ $data->noa_received_days }}
                            <?php $totalDays +=  $data->noa_received_days ; ?>

                            @if($data->noa_received_days > 1)
                                <strong class="red">({{$data->noa_received_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_approved_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->noa_received_remarks}}</td>
                </tr>

                <tr>
                    <td>PO Creation</td>
                    <td>
                        @if($data->po_create_date != null)
                        <?php $po_create_date = createCarbon('Y-m-d',$data->po_create_date); ?>
                        <a target="_blank" href="{{route('procurements.purchase-orders.show', $data->po_id)}}">
                            {{$po_create_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if(isset($noa_award_accepted_date))
                        @if($data->po_create_date != null)
                            {{ $data->po_days }}
                            <?php $totalDays +=  $data->po_days ; ?>

                            @if($data->po_days > 2)
                                <strong class="red">({{$data->po_days - 2}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_award_accepted_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->po_remarks}}</td>
                </tr>

                <tr>
                    <td>PO Fund Release</td>
                    <td>
                        @if($data->funding_received_date != null)
                        <?php $funding_received_date = createCarbon('Y-m-d',$data->funding_received_date); ?>
                        <a target="_blank" href="{{route('procurements.purchase-orders.show', $data->po_id)}}">
                            {{$funding_received_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if(isset($po_create_date))
                        @if($data->funding_received_date != null)
                            {{ $data->po_fund_days }}
                            <?php $totalDays +=  $data->po_fund_days ; ?>

                            @if($data->po_fund_days > 2)
                                <strong class="red">({{$data->po_fund_days - 2}})</strong>
                            @endif
                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $po_create_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->po_funding_remarks}}</td>
                </tr>

                <tr>
                    <td>PO MFO Release</td>
                    <td>
                        @if($data->mfo_received_date != null)
                        <?php $mfo_received_date = createCarbon('Y-m-d',$data->mfo_received_date); ?>
                        <a target="_blank" href="{{route('procurements.purchase-orders.show', $data->po_id)}}">
                            {{$mfo_received_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                        @if($data->mfo_received_date != null)
                            {{ $data->po_mfo_days }}
                            <?php $totalDays +=  $data->po_mfo_days ; ?>

                            @if($data->po_mfo_days > 2)
                                <strong class="red">({{$data->po_mfo_days - 2}})</strong>
                            @endif

                        @else
                            @if(isset($funding_received_date) )
                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $funding_received_date )}}
                            @endif
                        @endif
                    </td>
                    <td>{{$data->po_mfo_remarks}}</td>
                </tr>

                <tr>
                    <td>PO COA Approval</td>
                    <td>
                        @if($data->coa_approved_date != null)
                        <?php $coa_approved_date = createCarbon('Y-m-d',$data->coa_approved_date); ?>
                        <a target="_blank" href="{{route('procurements.purchase-orders.show', $data->po_id)}}">
                            {{$coa_approved_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                        @if(isset($mfo_received_date))
                            @if($data->coa_approved_date != null)
                                {{ $data->po_mfo_days }}
                                <?php $totalDays +=  $data->po_mfo_days ; ?>

                                @if($data->po_mfo_days > 1)
                                    <strong class="red">({{$data->po_mfo_days - 1}})</strong>
                                @endif
                            @else

                                {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $mfo_received_date )}}
                            @endif
                        @endif
                    </td>
                    <td>{{$data->po_coa_remarks}}</td>
                </tr>

                <tr>
                    <td>Prepare Notice to Proceed</td>
                    <td>
                        @if($data->ntp_date)
                            <?php $ntp_dates = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->ntp_date)->format('Y-m-d'); ?>
                            <a target="_blank" href="{{route('procurements.ntp.show', $data->ntp_id)}}">
                            {{  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->ntp_date)->format('d F Y') }}
                            </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($coa_approved_date) )
                        @if(isset($ntp_dates))
                            {{ $data->ntp_days }}
                            <?php $totalDays +=  $data->ntp_days ; ?>

                            @if($data->ntp_days > 1)
                                <strong class="red">({{$data->ntp_days - 1}})</strong>
                            @endif
                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $coa_approved_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->ntp_remarks}}</td>
                </tr>

                <tr>
                    <td>NTP Received</td>
                    <td>
                        @if($data->ntp_award_date != null)
                        <?php $ntp_award_date = createCarbon('Y-m-d',$data->ntp_award_date); ?>
                        <a target="_blank" href="{{route('procurements.ntp.show', $data->ntp_id)}}">
                            {{$ntp_award_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                        @if($data->ntp_award_date != null)
                            {{ $data->ntp_accepted_days }}
                            <?php $totalDays +=  $data->ntp_accepted_days ; ?>

                            @if($data->ntp_accepted_days > 1)
                                <strong class="red">({{$data->ntp_accepted_days - 1}})</strong>
                            @endif
                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ntp_date )}}
                        @endif
                    </td>
                    <td>{{$data->ntp_accepted_remarks}}</td>
                </tr>
                <tr>
                    <td>Create Notice Of Delivery</td>
                    <td>
                        @if($data->dr_date != null)
                        <?php $dr_date = createCarbon('Y-m-d',$data->dr_date); ?>
                        <a target="_blank" href="{{route('procurements.delivery-orders.show', $data->dr_id)}}">
                            {{$dr_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($ntp_award_date))
                        @if($data->dr_date != null)
                            {{ $data->dr_days }}
                            <?php $totalDays +=  $data->dr_days ; ?>

                            @if($data->dr_days > 1)
                                <strong class="red">({{$data->dr_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ntp_award_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->dr_remarks}}</td>
                </tr>
                <tr>
                    <td>Receive Delivery</td>
                    <td>
                        @if($data->delivery_date != null)
                        <?php $delivery_date = createCarbon('Y-m-d',$data->delivery_date); ?>
                        <a target="_blank" href="{{route('procurements.delivery-orders.show', $data->dr_id)}}">
                            {{$delivery_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>7</td>
                    <td>
                    @if(isset($dr_date))
                        @if($data->delivery_date != null)
                            {{ $data->dr_delivery_days }}
                            <?php $totalDays +=  $data->dr_delivery_days ; ?>

                            @if($data->dr_delivery_days > 7)
                                <strong class="red">({{$data->dr_delivery_days - 7}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->dr_delivery_remarks}}</td>
                </tr>
                <tr>
                    <td>COA Delivery</td>
                    <td>
                        @if($data->dr_coa_date != null)
                        <?php $dr_coa_date = createCarbon('Y-m-d',$data->dr_coa_date); ?>
                        <a target="_blank" href="{{route('procurements.delivery-orders.show', $data->dr_id)}}">
                            {{$dr_coa_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($delivery_date))
                        @if($data->dr_coa_date != null)
                            {{ $data->dr_dr_coa_days }}
                            <?php $totalDays +=  $data->dr_dr_coa_days ; ?>

                            @if($data->dr_dr_coa_days > 1)
                                <strong class="red">({{$data->dr_dr_coa_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $delivery_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->dr_dr_coa_remarks}}</td>
                </tr>

                <tr>
                    <td>Delivery Inspection</td>
                    <td>
                        @if($data->dr_inspection != null)
                        <?php $dr_inspection = createCarbon('Y-m-d',$data->dr_inspection); ?>
                        <a target="_blank" href="{{route('procurements.inspection-and-acceptance.show', $data->tiac_id)}}">
                            {{$dr_inspection->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($dr_coa_date))
                        @if($data->dr_inspection != null)
                            {{ $data->tiac_days }}
                            <?php $totalDays +=  $data->tiac_days ; ?>

                            @if($data->tiac_days > 1)
                                <strong class="red">({{$data->tiac_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_coa_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->tiac_remarks}}</td>
                </tr>

                <tr>
                    <td>IAR Acceptance</td>
                    <td>
                        @if($data->iar_accepted_date != null)
                        <?php $iar_accepted_date = createCarbon('Y-m-d',$data->iar_accepted_date); ?>
                        <a target="_blank" href="{{route('procurements.inspection-and-acceptance.show', $data->tiac_id)}}">
                            {{$iar_accepted_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($dr_inspection))
                        @if($data->iar_accepted_date != null)
                            {{ $data->tiac_accept_days }}
                            <?php $totalDays +=  $data->tiac_accept_days ; ?>

                            @if($data->tiac_accept_days > 1)
                                <strong class="red">({{$data->tiac_accept_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_inspection )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->tiac_accept_remarks}}</td>
                </tr>
                <tr>
                    <td>DR Inspection Start</td>
                    <td>
                        @if($data->di_start != null)
                        <?php $di_start = createCarbon('Y-m-d',$data->di_start); ?>
                        <a target="_blank" href="{{route('procurements.delivered-inspections.show', $data->diir_id)}}">
                            {{$di_start->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($iar_accepted_date))
                        @if($data->di_start != null)
                            {{ $data->diir_days }}
                            <?php $totalDays +=  $data->diir_days ; ?>

                            @if($data->diir_days > 1)
                                <strong class="red">({{$data->diir_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $iar_accepted_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->diir_remarks}}</td>
                </tr>

                <tr>
                    <td>DR Inspection Close</td>

                    <td>
                        @if($data->di_close != null)
                        <?php $di_close = createCarbon('Y-m-d',$data->di_close); ?>
                        <a target="_blank" href="{{route('procurements.delivered-inspections.show', $data->diir_id)}}">
                            {{$di_close->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($di_start))
                        @if($data->di_close != null)

                            {{ $data->diir_close_days }}
                            <?php $totalDays +=  $data->diir_close_days ; ?>

                            @if($data->diir_close_days > 1)
                                <strong class="red">({{$data->diir_close_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $di_start )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->diir_close_remarks}}</td>
                </tr>

                <tr>
                    <td>Prepare Voucher</td>
                    <td>
                        @if($data->v_transaction_date != null)
                        <?php $v_transaction_date = createCarbon('Y-m-d',$data->v_transaction_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$v_transaction_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($di_close))
                        @if($data->v_transaction_date != null)

                            {{ $data->vou_days }}
                            <?php $totalDays +=  $data->vou_days ; ?>

                            @if($data->vou_days > 1)
                                <strong class="red">({{$data->vou_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $di_close )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_remarks}}</td>
                </tr>

                <tr>
                    <td>Voucher Preaudit</td>
                    <td>
                        @if($data->preaudit_date != null)
                        <?php $preaudit_date = createCarbon('Y-m-d',$data->preaudit_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$preaudit_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($v_transaction_date))
                        @if($data->preaudit_date != null)
                            {{ $data->vou_preaudit_days }}
                            <?php $totalDays +=  $data->vou_preaudit_days ; ?>

                            @if($data->vou_preaudit_days > 1)
                                <strong class="red">({{$data->vou_preaudit_days - 1}})</strong>
                            @endif


                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $v_transaction_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_preaudit_remarks}}</td>
                </tr>

                <tr>
                    <td>Voucher Certify</td>
                    <td>
                        @if($data->certify_date != null)
                        <?php $certify_date = createCarbon('Y-m-d',$data->certify_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$certify_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>

                    <td>1</td>
                    <td>
                    @if(isset($preaudit_date))
                        @if($data->certify_date != null)

                            {{ $data->vou_certify_days }}
                            <?php $totalDays +=  $data->vou_certify_days ; ?>

                            @if($data->vou_certify_days > 1)
                                <strong class="red">({{$data->vou_certify_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $preaudit_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_certify_remarks}}</td>
                </tr>

                <tr>
                    <td>Voucher JEV</td>
                    <td>
                        @if($data->journal_entry_date != null)
                        <?php $journal_entry_date = createCarbon('Y-m-d',$data->journal_entry_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$journal_entry_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($certify_date))
                        @if($data->journal_entry_date != null)
                            {{ $data->vou_jev_days }}
                            <?php $totalDays +=  $data->vou_jev_days ; ?>

                            @if($data->vou_jev_days > 1)
                                <strong class="red">({{$data->vou_jev_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $certify_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_jev_remarks}}</td>
                </tr>
                <tr>
                    <td>Voucher Approval</td>
                    <td>
                        @if($data->vou_approval_date != null)
                        <?php $vou_approval_date = createCarbon('Y-m-d',$data->vou_approval_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$vou_approval_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($journal_entry_date))
                        @if($data->vou_approval_date != null)
                            {{ $data->vou_approved_days }}
                            <?php $totalDays +=  $data->vou_approved_days ; ?>

                            @if($data->vou_approved_days > 1)
                                <strong class="red">({{$data->vou_approved_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $journal_entry_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_approved_remarks}}</td>
                </tr>
                <tr>
                    <td>Voucher Release</td>
                    <td>
                        @if($data->vou_release != null)
                        <?php $vou_release = createCarbon('Y-m-d',$data->vou_release); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$vou_release->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($vou_approval_date) )
                        @if($data->vou_release != null)
                            {{ $data->vou_released_days }}
                            <?php $totalDays +=  $data->vou_released_days ; ?>

                            @if($data->vou_released_days > 1)
                                <strong class="red">({{$data->vou_released_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $vou_approval_date )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_released_remarks}}</td>
                </tr>

                <tr>
                    <td>Voucher Received/Complete RFQ</td>
                    <td>
                        @if($data->vou_received != null)
                        <?php $vou_received = createCarbon('Y-m-d',$data->vou_received); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$vou_received->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($vou_release))
                        @if($data->vou_received != null)
                            {{ $data->vou_received_days }}
                            <?php $totalDays +=  $data->vou_received_days ; ?>

                            @if($data->vou_received_days > 1)
                                <strong class="red">({{$data->vou_received_days - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $vou_release )}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_received_remarks}}</td>
                </tr>

                <tr>
                    <td>Total</td>
                    <td></td>
                    <td>Working Days</td>
                    <td>{{$totalDays}}</td>
                    <td></td>
                </tr>

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