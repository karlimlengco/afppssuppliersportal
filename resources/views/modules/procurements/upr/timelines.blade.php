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
        $today          =   \Carbon\Carbon::createFromFormat('Y-m-d', $today);
        $upr_created    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->date_prepared);
    ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th  style="text-align:center; width:150px">Latest Allowable Time</th>
                    <th>Day/s</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>UPR</td>
                    <td>{{ $data->date_prepared}}</td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Create RFQ</td>
                    <td>{{ $data->rfq_created_at }}</td>
                    <td style="text-align:center">1</td>
                    <td>
                        @if($data->rfq_created_at != null)
                            <?php $rfq_created_at    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->rfq_created_at); ?>
                            {{ $d = $rfq_created_at->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $upr_created )}}
                            <?php $totalDays +=  $d; ?>
                            <strong class="red">({{$d - 1}})</strong>
                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $upr_created )}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Close RFQ</td>
                    <td>
                        @if($data->rfq_completed_at)
                        <?php $rfq_completed_ats = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->rfq_completed_at)->format('Y-m-d'); ?>
                        {{  $rfq_completed_ats }}
                        @endif
                    </td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($rfq_completed_ats))
                        @if($rfq_completed_ats != null)
                            <?php $rfq_completed_at    =   \Carbon\Carbon::createFromFormat('Y-m-d', $rfq_completed_ats); ?>
                            {{ $d = $rfq_completed_at->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $rfq_created_at )}}
                            <?php $totalDays +=  $d; ?>
                            <strong class="red">({{$d - 1}})</strong>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $rfq_created_at )}}
                        @endif
                    @endif

                    </td>
                </tr>

                <tr>
                    <td>RFQ Create Invitation</td>
                    <td>{{$data->ispq_transaction_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($rfq_completed_at))
                        @if($data->ispq_transaction_date != null)
                            <?php $ispq_transaction_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date); ?>
                            {{ $d = $ispq_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $rfq_completed_at )}}

                            <?php $totalDays +=  $d; ?>
                            @if($d < 1)
                            <strong class="red">({{$d - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $rfq_completed_at )}}
                        @endif
                    @endif

                    </td>
                </tr>

                <tr>
                    <td>Philgeps posting</td>
                    <td>{{$data->pp_completed_at}}</td>
                    <td style="text-align:center">3</td>
                    <td>
                    @if(isset($ispq_transaction_date))
                        @if($data->pp_completed_at != null)
                            <?php $pp_completed_at    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->pp_completed_at); ?>
                            {{ $d = $pp_completed_at->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ispq_transaction_date )}}

                            <?php $totalDays +=  $d; ?>
                            <strong class="red">({{$d - 3}})</strong>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ispq_transaction_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Canvassing</td>
                    <td>{{$data->canvass_start_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($ispq_transaction_date))
                        @if($data->canvass_start_date != null)
                            <?php $canvass_start_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->canvass_start_date); ?>
                            {{ $d = $canvass_start_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ispq_transaction_date )}}

                            <?php $totalDays +=  $d; ?>
                            @if($d > 1)
                            <strong class="red">({{$d - 1}})</strong>
                            @endif

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ispq_transaction_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Awarding</td>
                    <td>
                        @if($data->noa_award_date)
                            <?php $noa_award_dates = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->noa_award_date)->format('Y-m-d'); ?>
                            {{  $noa_award_dates }}
                        @endif
                    </td>
                    <td style="text-align:center">2</td>
                    <td>
                    @if(isset($canvass_start_date))
                        @if($noa_award_dates != null && $data->noa_award_date)
                            <?php $noa_award_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $noa_award_dates); ?>
                            {{ $d = $noa_award_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $canvass_start_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $canvass_start_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>NOA Approved</td>
                    <td>{{$data->noa_approved_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($noa_award_date))
                        @if($data->noa_approved_date != null)
                            <?php $noa_approved_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date); ?>
                            {{ $d = $noa_approved_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_award_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_award_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>NOA Receieved</td>
                    <td>{{$data->noa_award_accepted_date}}</td>
                    <td style="text-align:center">1</td>

                    <td>
                    @if(isset($noa_approved_date))
                        @if($data->noa_award_accepted_date != null)
                            <?php $noa_award_accepted_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date); ?>
                            {{ $d = $noa_award_accepted_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_approved_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_approved_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>PO Creation</td>
                    <td>{{$data->po_create_date}}</td>
                    <td style="text-align:center">2</td>
                    <td>
                    @if(isset($noa_award_accepted_date))
                        @if($data->po_create_date != null)
                            <?php $po_create_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->po_create_date); ?>
                            {{ $d = $po_create_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_award_accepted_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $noa_award_accepted_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>PO Fund Release</td>
                    <td>{{$data->funding_received_date}}</td>
                    <td style="text-align:center">2</td>
                    <td>
                    @if(isset($po_create_date))
                        @if($data->funding_received_date != null)
                            <?php $funding_received_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->funding_received_date); ?>
                            {{ $d = $funding_received_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $po_create_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $po_create_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>PO MFO Release</td>
                    <td>{{$data->mfo_received_date}}</td>
                    <td style="text-align:center">2</td>
                    <td>
                    @if(isset($funding_received_date))
                        @if($data->mfo_received_date != null)
                            <?php $mfo_received_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_received_date); ?>
                            {{ $d = $mfo_received_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $funding_received_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else
                            @if(isset($funding_received_date) )
                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $funding_received_date )}}
                            @endif
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>PO COA Approval</td>
                    <td>{{$data->coa_approved_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                        @if(isset($mfo_received_date))
                            @if($data->coa_approved_date != null)
                                <?php $coa_approved_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->coa_approved_date); ?>
                                {{ $d = $coa_approved_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $mfo_received_date )}}

                                <?php $totalDays +=  $d; ?>
                            @else

                                {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $mfo_received_date )}}
                            @endif
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>NTP Create</td>
                    <td>
                        @if($data->ntp_date)
                            <?php $ntp_dates = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->ntp_date)->format('Y-m-d'); ?>
                            {{  $ntp_dates }}
                        @endif
                    </td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($coa_approved_date) )
                        @if(isset($ntp_dates))
                            <?php $ntp_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $ntp_dates); ?>
                            {{ $d = $ntp_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $coa_approved_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $coa_approved_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>NTP Award</td>
                    <td>{{$data->ntp_award_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($ntp_date))
                        @if($data->ntp_award_date != null)
                            <?php $ntp_award_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->ntp_award_date); ?>
                            {{ $d = $ntp_award_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ntp_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ntp_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Create Notice Of Delivery</td>
                    <td>{{$data->dr_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($ntp_award_date))
                        @if($data->dr_date != null)
                            <?php $dr_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_date); ?>
                            {{ $d = $dr_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ntp_award_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $ntp_award_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Receive Delivery</td>
                    <td>{{$data->delivery_date}}</td>
                    <td style="text-align:center">7</td>
                    <td>
                    @if(isset($dr_date))
                        @if($data->delivery_date != null)
                            <?php $delivery_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date); ?>
                            {{ $d = $delivery_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>COA Delivery</td>
                    <td>{{$data->dr_coa_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($delivery_date))
                        @if($data->dr_coa_date != null)
                            <?php $dr_coa_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date); ?>
                            {{ $d = $dr_coa_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $delivery_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $delivery_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Delivery Inspection</td>
                    <td>{{$data->dr_inspection}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($dr_coa_date))
                        @if($data->dr_inspection != null)
                            <?php $dr_inspection    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection); ?>
                            {{ $dr_inspection->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_coa_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_coa_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>IAR Acceptance</td>
                    <td>{{$data->iar_accepted_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($dr_inspection))
                        @if($data->iar_accepted_date != null)
                            <?php $iar_accepted_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->iar_accepted_date); ?>
                            {{ $d = $iar_accepted_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_inspection )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $dr_inspection )}}
                        @endif
                    @endif
                    </td>
                </tr>


                <tr>
                    <td>DR Inspection Start</td>
                    <td>{{$data->di_start}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($iar_accepted_date))
                        @if($data->di_start != null)
                            <?php $di_start    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start); ?>
                            {{ $d = $di_start->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $iar_accepted_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $iar_accepted_date )}}
                        {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $iar_accepted_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>DR Inspection Close</td>
                    <td>{{$data->di_close}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($di_start))
                        @if($data->di_close != null)
                            <?php $di_close    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close); ?>
                            {{ $d = $di_close->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $di_start )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $di_start )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Voucher Create</td>
                    <td>{{$data->v_transaction_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($di_close))
                        @if($data->v_transaction_date != null)
                            <?php $v_transaction_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->v_transaction_date); ?>
                            {{ $d = $v_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $di_close )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $di_close )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Voucher Preaudit</td>
                    <td>{{$data->preaudit_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($v_transaction_date))
                        @if($data->preaudit_date != null)
                            <?php $preaudit_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->preaudit_date); ?>
                            {{ $d = $preaudit_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $v_transaction_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $v_transaction_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Voucher Certify</td>
                    <td>{{$data->certify_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($preaudit_date))
                        @if($data->certify_date != null)
                            <?php $certify_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->certify_date); ?>
                            {{ $d = $certify_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $preaudit_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $preaudit_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Voucher JEV</td>
                    <td>{{$data->journal_entry_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($certify_date))
                        @if($data->journal_entry_date != null)
                            <?php $journal_entry_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->journal_entry_date); ?>
                            {{ $d = $journal_entry_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $certify_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $certify_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Voucher Approval</td>
                    <td>{{$data->vou_approval_date}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($journal_entry_date))
                        @if($data->vou_approval_date != null)
                            <?php $vou_approval_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_approval_date); ?>
                            {{ $d = $vou_approval_date->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $journal_entry_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $journal_entry_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Voucher Release</td>
                    <td>{{$data->vou_release}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($vou_approval_date) )
                        @if($data->vou_release != null)
                            <?php $vou_release    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release); ?>
                            {{ $d = $vou_release->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $vou_approval_date )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $vou_approval_date )}}
                        @endif
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Voucher Received/Complete RFQ</td>
                    <td>{{$data->vou_received}}</td>
                    <td style="text-align:center">1</td>
                    <td>
                    @if(isset($vou_release))
                        @if($data->vou_received != null)
                            <?php $vou_received    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_received); ?>
                            {{ $vou_received->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $vou_release )}}

                            <?php $totalDays +=  $d; ?>

                        @else

                            {{ $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $vou_release )}}
                        @endif
                    @endif
                    </td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>Working Days</td>
                    <td>{{$totalDays}}</td>
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