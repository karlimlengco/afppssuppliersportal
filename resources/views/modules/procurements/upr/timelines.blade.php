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

@section('contents')
<div class="row">
    <div class="six columns utility utility--align-right" style="margin-bottom:0px" >
        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
    <div class="six columns utility"  style="margin-bottom:0px; text-align: left" >
        <div class="eleven columns">{!! Form::selectField('upr_number', '', $upr_list, ['id'=>'upr_id'])!!}</div>
        <div class="one columns">
            <a href="#" id="printme" class="button" style="margin-top:5px"> <i class="nc-icon-mini arrows-2_square-download"></i></a>
        </div>

    </div>
</div>

<div class="data-panel" style="padding:10px; margin-bottom:10px; background: rgb(34, 34, 34);color:white; z-index:99999">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item" style="color:white;"> <strong class="data-panel__list__item__label" style="color:white;font-weight:800; text-transform:capitalize">UPR No :</strong> {{$data->upr_number}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item" style="color:white;"> <strong class="data-panel__list__item__label" style="color:white;font-weight:800; text-transform:capitalize">Project Name / Activity:</strong> {{$data->project_name}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item" style="color:white;"> <strong class="data-panel__list__item__label" style="color:white;font-weight:800; text-transform:capitalize">ABC:</strong>Php {{number_format($data->total_amount,2)}} </li>
        </ul>
    </div>
</div>

    <?php
        $totalDays      =   0;
        $today          =   \Carbon\Carbon::now()->format('Y-m-d');
        $today          =   createCarbon('Y-m-d', $today);
        $upr_created    =   $data->date_processed;
        $next_date      =   $data->date_processed;
        if($data->next_due)
        {
            $next_date      =   createCarbon('Y-m-d', $data->next_due);
        }
    ?>

        <table class="table">
            <thead>
                <tr>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">Stage</th>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">Date</th>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">Allowable Time</th>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">W Day/s </th>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">Total Days </th>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">Justification/Remarks</th>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">Action Taken</th>
                    <th style="background: rgb(34, 34, 34);color:white; z-index:99999">Print</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1. UPR Receipt</td>
                    <td>{{ $data->date_processed->format('d F Y')}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <a target="_blank"
                            href="{{route('procurements.unit-purchase-requests.print', $data->id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                    </td>
                </tr>
                @if($data->mode_of_procurement != 'public_bidding')

                <tr>
                    <td>2. ITSPQ</td>
                    <td>
                        @if($data->ispq_transaction_date != null)
                            <a target="_blank" href="{{route('procurements.ispq.edit', $data->ispq_id)}}">
                            {{createCarbon('Y-m-d',$data->ispq_transaction_date)->format('d F Y')}}
                            </a>
                        @endif
                    </td>
                    <td>3</td>
                    <td>
                    @if($data->ispq_transaction_date != null)
                        {{ $data->ispq_days }}


                        @if($data->ispq_days > 3)
                            <strong class="red" tooltip="Delay">({{$data->ispq_days - 3}})</strong>
                        @endif

                    @else

                        <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>
                        {{$d}}


                        @if($d > 3)
                            <strong class="red" tooltip="Delay">({{$d - 3}})</strong>
                        @endif
                    @endif

                    </td>
                    <td>{{ $data->ispq_days }}</td>
                    <td>{{$data->ispq_remarks}}</td>
                    <td>{{$data->ispq_action}}</td>

                    <td>
                        @if($data->ispq_transaction_date != null)
                        <a target="_blank"
                            href="{{route('procurements.ispq.print', $data->ispq_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>3. Philgeps posting</td>
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
                        @if($data->total_amount > 50000)
                          @if($data->pp_completed_at != null)
                              {{ $data->pp_days }}

                              @if($data->pp_days > 3)
                                  <strong class="red" tooltip="Delay">({{$data->pp_days - 3}})</strong>
                              @endif

                          @else
                              <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                              {{-- {{ ($d >= 3) ?  $d - 3 : $d }} --}}
                              {{$d}}

                              @if($d > 3)
                                  <strong class="red" tooltip="Delay">({{$d - 3}})</strong>
                              @endif

                          @endif
                        @endif
                    </td>
                    <td>{{ $data->pp_days }}</td>
                    <td>{{$data->pp_remarks}}</td>
                    <td>{{$data->pp_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>4. RFQ</td>
                    <td>
                        @if($data->rfq_completed_at != null)
                            <?php $rfq_completed_at = createCarbon('Y-m-d H:i:s',$data->rfq_completed_at)->format('d F Y'); ?>
                            <?php $rfq_completed_ats = createCarbon('Y-m-d H:i:s',$data->rfq_completed_at)->format('Y-m-d'); ?>
                            <a target="_blank" href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}">
                                {{  $rfq_completed_at }}
                            </a>

                        @endif
                    </td>
                    <td>3</td>
                    <td>
                        @if(isset($rfq_completed_ats) && $rfq_completed_ats != null)
                            {{ $data->rfq_closed_days }}
                            <?php $totalDays +=  $data->rfq_closed_days ; ?>

                            @if($data->rfq_closed_days >  3)
                                <strong class="red" tooltip="Delay">({{$data->rfq_closed_days - 3}})</strong>
                            @endif

                        @else
                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ) ;?>
                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}

                            @if($d > 3)
                                <strong class="red" tooltip="Delay">({{$d - 3}})</strong>
                            @endif
                        @endif

                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->rfq_close_remarks}}</td>
                    <td>{{$data->rfq_close_action}}</td>
                    <td>


                        @if($data->rfq_created_at != null)
                        <a target="_blank"
                            href="{{route('procurements.blank-rfq.print', $data->rfq_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>5. Canvass</td>
                    <td>
                        @if($data->canvass_start_date != null)
                            <?php $canvass_start_date = createCarbon('Y-m-d',$data->canvass_start_date); ?>
                        <a target="_blank" href="{{route('procurements.canvassing.show', $data->canvass_id)}}">
                            {{$canvass_start_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if($data->rfq_completed_at != null)
                        @if(isset($canvass_start_date) )
                            {{ $data->canvass_days }}
                            <?php $totalDays +=  $data->canvass_days ; ?>

                            @if($data->canvass_days > 2)
                                <strong class="red" tooltip="Delay">({{$data->canvass_days - 2}})</strong>
                            @endif

                        @else
                            @if($next_date < $today)
                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                                {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                {{$d}}
                                @if($d > 2)
                                    <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                                @endif
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->canvass_remarks}}</td>
                    <td>{{$data->canvass_action}}</td>

                    <td>
                        @if($data->canvass_start_date != null)
                        <a target="_blank"
                            href="{{route('procurements.canvassing.print', $data->canvass_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>



                @else
                    @if(count($data->documents) != 0)
                        @foreach($data->documents as $docu)
                        <tr>
                            <td>2. Document Acceptance</td>
                            <td>
                                @if($docu->approved_date != null)
                                <?php $doc_transaction_date  =  createCarbon('Y-m-d',$docu->approved_date); ?>
                                <a target="_blank" href="{{route('biddings.document-acceptance.show', $docu->id)}}">
                                    {{ $doc_transaction_date->format('d F Y') }}
                                </a>
                                @else

                                <?php $doc_transaction_date  =  createCarbon('Y-m-d',$docu->return_date); ?>
                                <a target="_blank" href="{{route('biddings.document-acceptance.show', $docu->id)}}">
                                    {{ $doc_transaction_date->format('d F Y') }}
                                </a>
                                @endif
                            </td>
                            <td >1</td>
                            <td>
                                {{ $docu->days }}

                                @if($docu->days > 1)
                                    <strong class="red" tooltip="Delay">({{$docu->days - 1}})</strong>
                                @endif
                                <?php $totalDays +=  $docu->days ; ?>
                            </td>
                            <td>{{$docu->remarks}}</td>
                            <td>{{$docu->action}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    @endif


                    {{-- Pre Proc --}}

                    @if(count($data->preprocs) > 1)
                        @foreach($data->preprocs as $proc)
                            <tr>
                                <td>3. Pre Proc Conference</td>
                                <td>
                                        <?php $pre_proc_date  =  createCarbon('Y-m-d',$proc->pre_proc_date); ?>
                                            {{ $pre_proc_date->format('d F Y') }}
                                </td>
                                <td >1</td>
                                <td>

                                    {{ $proc->days }}
                                    <?php $totalDays +=  $proc->days ; ?>

                                    @if($proc->days > 1)
                                        <strong class="red" tooltip="Delay">({{$proc->days - 1}})</strong>
                                    @endif

                                </td>
                                <td>
                                    @if($proc != null){{$proc->remarks}}@endif</td>
                                <td>
                                    @if($proc != null){{$proc->action}}@endif</td>
                                <td>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>3. Pre Proc Conference</td>
                            <td>
                                @if($data->preproc != null)
                                    <?php $pre_proc_date  =  createCarbon('Y-m-d',$data->preproc->pre_proc_date); ?>
                                        {{ $pre_proc_date->format('d F Y') }}
                                @endif
                            </td>
                            <td >1</td>
                            <td>
                                @if($data->preproc != null)
                                    {{ $data->preproc->days }}
                                    <?php $totalDays +=  $data->preproc->days ; ?>

                                    @if($data->preproc->days > 1)
                                        <strong class="red" tooltip="Delay">({{$data->preproc->days - 1}})</strong>
                                    @endif

                                    @else
                                    <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                                    {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                    {{$d}}
                                    @if($d > 1)
                                        <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                                    @endif
                                @endif

                            </td>
                            <td>
                                @if($data->preproc != null){{$data->preproc->remarks}}@endif</td>
                            <td>
                                @if($data->preproc != null){{$data->preproc->action}}@endif</td>
                            <td>

                            </td>
                        </tr>
                    @endif
                    {{-- Pre Proc --}}

                    {{-- ITB --}}


                    @if(count($data->itbs) > 1)
                        @foreach($data->itbs as $itb)
                            <tr>
                                <td>4. Invitation To Bid</td>
                                <td>
                                    <?php $itb_approved_date  =  createCarbon('Y-m-d',$itb->approved_date); ?>
                                            {{ $itb_approved_date->format('d F Y') }}
                                </td>
                                <td>7</td>
                                <td>
                                    {{ $itb->days }}
                                    <?php $totalDays +=  $itb->days ; ?>

                                    @if($itb->days > 7)
                                        <strong class="red" tooltip="Delay">({{$itb->days - 7}})</strong>
                                    @endif

                                </td>
                                <td>
                                    @if($itb != null){{$itb->remarks}}@endif</td>
                                <td>
                                    @if($itb != null){{$itb->action}}@endif</td>
                                <td>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>4. Invitation To Bid</td>
                            <td>
                                @if($data->itb != null)
                                    <?php $itb_approved_date  =  createCarbon('Y-m-d',$data->itb->approved_date); ?>
                                        {{ $itb_approved_date->format('d F Y') }}
                                @endif
                            </td>
                            <td>7</td>
                            <td>
                                @if($data->itb != null)
                                    {{ $data->itb->days }}
                                    <?php $totalDays +=  $data->itb->days ; ?>

                                    @if($data->itb->days > 7)
                                        <strong class="red" tooltip="Delay">({{$data->itb->days - 7}})</strong>
                                    @endif

                                    @else
                                    @if($data->preproc != null)
                                        <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                                        {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                        {{$d}}
                                        @if($d > 7)
                                            <strong class="red" tooltip="Delay">({{$d - 7}})</strong>
                                        @endif
                                    @endif
                                @endif

                            </td>
                            <td>
                                @if($data->itb != null){{$data->itb->remarks}}@endif</td>
                            <td>
                                @if($data->itb != null){{$data->itb->action}}@endif</td>
                            <td>

                            </td>
                        </tr>
                    @endif
                    {{-- ITB --}}

                    {{-- Philgeps --}}

                    @if(count($data->philgeps_many) > 1)
                        @foreach($data->philgeps_many as $pm)
                        <tr>
                            <td>5. PhilGeps Posting</td>
                            <td>
                                <?php $philgeps_date  =  createCarbon('Y-m-d',$pm->transaction_date); ?>
                                {{ $philgeps_date->format('d F Y') }}
                            </td>
                            <td >7</td>
                            <td>
                                    {{ $pm->days }}
                                    <?php $totalDays +=  $pm->days ; ?>

                                    @if($pm->days > 7)
                                        <strong class="red" tooltip="Delay">({{$pm->days - 7}})</strong>
                                    @endif

                            </td>
                            <td>{{$pm->remarks}}</td>
                            <td>{{$pm->action}}</td>
                            <td>

                            </td>
                        </tr>
                        @endforeach

                    @else
                        @if($data->philgeps != null)
                        <tr>
                            <td>5. PhilGeps Posting</td>
                            <td>
                                <?php $philgeps_date  =  createCarbon('Y-m-d',$data->philgeps->transaction_date); ?>
                                {{ $philgeps_date->format('d F Y') }}
                            </td>
                            <td >7</td>
                            <td>
                                    {{ $data->philgeps->days }}
                                    <?php $totalDays +=  $data->philgeps->days ; ?>

                                    @if($data->philgeps->days > 7)
                                        <strong class="red" tooltip="Delay">({{$data->philgeps->days - 7}})</strong>
                                    @endif

                            </td>
                            <td>{{$data->philgeps->remarks}}</td>
                            <td>{{$data->philgeps->action}}</td>
                            <td>

                            </td>
                        </tr>
                        @endif
                    @endif
                    {{-- Philgeps --}}


                    {{-- Pre Bid Conference --}}
                    @if(count($data->bid_conferences) > 1)
                        @foreach($data->bid_conferences as $bids)
                        <tr>
                            <td>6. Pre Bid Conference</td>
                            <td>
                                <?php $rfq_created_at  =  createCarbon('Y-m-d',$bids->transaction_date); ?>
                                    <a target="_blank" href="{{route('biddings.pre-bids.show', $bids->id)}}">
                                        {{ $rfq_created_at->format('d F Y') }}
                                    </a>
                            </td>
                            <td >1</td>
                            <td>
                                    {{ $bids->days }}
                                    <?php $totalDays +=  $bids->days ; ?>

                            </td>
                            <td>

                                    {{$bids->remarks}}
                            </td>
                            <td>
                                    {{$bids->action}}
                            </td>
                            <td>

                            </td>
                        </tr>
                        @endforeach

                    @else

                        <tr>
                            <td>6. Pre Bid Conference</td>
                            <td>
                                @if($data->bid_conference != null)
                                <?php $rfq_created_at  =  createCarbon('Y-m-d',$data->bid_conference->transaction_date); ?>
                                    <a target="_blank" href="{{route('biddings.pre-bids.show', $data->bid_conference->id)}}">
                                        {{ $rfq_created_at->format('d F Y') }}
                                    </a>
                                @endif
                            </td>
                            <td >1</td>
                            <td>
                            @if($data->itb != null)
                                @if($data->bid_conference != null)
                                    {{ $data->bid_conference->days }}
                                    <?php $totalDays +=  $data->bid_conference->days ; ?>

                                @else
                                    @if($data->philgeps != null)
                                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                            {{$d}}
                                        @if($d > 1)
                                            <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                                        @endif
                                    @endif
                                @endif
                            @endif
                            </td>
                            <td>

                                @if($data->bid_conference != null)
                                    {{$data->bid_conference->remarks}}
                                @endif
                            </td>
                            <td>
                                @if($data->bid_conference != null)
                                    {{$data->bid_conference->action}}
                                @endif
                            </td>
                            <td>

                            </td>
                        </tr>
                    @endif
                    {{-- Pre bid conf --}}

                    {{-- SOBE --}}

                    @if(count($data->bid_opens) > 1)
                        @foreach($data->bid_opens as $bops)
                            <tr>
                                <td>7. SOBE</td>
                                <td>
                                    @if($bops != null)
                                    <?php $bid_open  =  createCarbon('Y-m-d',$bops->transaction_date); ?>
                                        <a target="_blank" href="{{route('biddings.bid-openings.show', $bops->id)}}">
                                            {{ $bid_open->format('d F Y') }}
                                        </a>
                                    @endif
                                </td>
                                <td >45</td>
                                <td>
                                    {{ $bops->days }}
                                    <?php $totalDays +=  $bops->days ; ?>

                                    @if($bops->days > 45)
                                        <strong class="red" tooltip="Delay">({{$bops->days - 45}})</strong>
                                    @endif

                                </td>
                                <td>
                                    @if($bops != null)
                                        {{$bops->remarks}}
                                    @endif
                                </td>
                                <td>
                                    @if($bops != null)
                                        {{$bops->action}}
                                    @endif
                                </td>
                                <td>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>7. SOBE</td>
                            <td>
                                @if($data->bid_open != null)
                                <?php $bid_open  =  createCarbon('Y-m-d',$data->bid_open->transaction_date); ?>
                                    <a target="_blank" href="{{route('biddings.bid-openings.show', $data->bid_open->id)}}">
                                        {{ $bid_open->format('d F Y') }}
                                    </a>
                                @endif
                            </td>
                            <td >45</td>
                            <td>
                            @if($data->bid_conference != null)
                                @if($data->bid_open != null)
                                    {{ $data->bid_open->days }}
                                    <?php $totalDays +=  $data->bid_open->days ; ?>

                                    @if($data->bid_open->days > 45)
                                        <strong class="red" tooltip="Delay">({{$data->bid_open->days - 45}})</strong>
                                    @endif

                                @else
                                        <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                                        {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                        {{$d}}
                                    @if($d > 45)
                                        <strong class="red" tooltip="Delay">({{$d - 45}})</strong>
                                    @endif
                                @endif
                            @endif
                            </td>
                            <td>
                                @if($data->bid_open != null)
                                    {{$data->bid_open->remarks}}
                                @endif
                            </td>
                            <td>
                                @if($data->bid_open != null)
                                    {{$data->bid_open->action}}
                                @endif
                            </td>
                            <td>

                            </td>
                        </tr>
                    @endif
                    {{-- SOBE --}}


                    {{-- SOBE --}}
                    <tr>
                        <td>8. Close SOBE</td>
                        <td>
                            @if($data->bid_open != null && $data->bid_open->closing_date != null)
                            <?php $bid_close  =  createCarbon('Y-m-d',$data->bid_open->closing_date); ?>
                                <a target="_blank" href="{{route('biddings.bid-openings.show', $data->bid_open->id)}}">
                                    {{ $bid_close->format('d F Y') }}
                                </a>
                            @endif
                        </td>
                        <td >7</td>
                        <td>
                        @if($data->bid_conference != null)
                            @if($data->bid_open != null && $data->bid_open->closing_date != null)
                                <?php  $closeDays =  $bid_open->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $bid_close ); ?>
                                {{ $closeDays }}
                                <?php $totalDays +=  $closeDays ; ?>

                                @if($closeDays > 7)
                                    <strong class="red" tooltip="Delay">({{$closeDays - 7}})</strong>
                                @endif

                            @else
                                    <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                                    {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                    {{$d}}
                                @if($d > 7)
                                    <strong class="red" tooltip="Delay">({{$d - 7}})</strong>
                                @endif
                            @endif
                        @endif
                        </td>
                        <td>
                            @if($data->bid_open != null)
                                {{$data->bid_open->remarks}}
                            @endif
                        </td>
                        <td>
                            @if($data->bid_open != null)
                                {{$data->bid_open->action}}
                            @endif
                        </td>
                        <td>

                        </td>
                    </tr>
                    {{-- SOBE --}}

                    {{-- Post Qualification --}}
                    @if(count($data->post_quals) > 1)
                        @foreach($data->post_quals as $pqs)

                            <tr>
                                <td>9. Post Qualification</td>
                                <td>
                                    <?php $rfq_created_at  =  createCarbon('Y-m-d',$pqs->transaction_date); ?>
                                        <a target="_blank" href="{{route('biddings.post-qualifications.show', $pqs->id)}}">
                                            {{ $rfq_created_at->format('d F Y') }}
                                        </a>
                                </td>
                                <td >45</td>
                                <td>
                                    {{ $pqs->days }}
                                    <?php $totalDays +=  $pqs->days ; ?>

                                    @if($pqs->days > 45)
                                        <strong class="red" tooltip="Delay">({{$pqs->days - 45}})</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($pqs != null) {{$pqs->remarks}} @endif
                                </td>
                                <td>
                                    @if($pqs != null) {{$pqs->action}} @endif
                                </td>
                                <td>
                                </td>
                            </tr>

                        @endforeach
                    @else
                        <tr>
                            <td>9. Post Qualification</td>
                            <td>
                                @if($data->post_qual != null)
                                <?php $rfq_created_at  =  createCarbon('Y-m-d',$data->post_qual->transaction_date); ?>
                                    <a target="_blank" href="{{route('biddings.post-qualifications.show', $data->post_qual->id)}}">
                                        {{ $rfq_created_at->format('d F Y') }}
                                    </a>
                                @endif
                            </td>
                            <td >45</td>
                            <td>
                                @if($data->bid_open != null)
                                    @if($data->post_qual != null)
                                        {{ $data->post_qual->days }}
                                        <?php $totalDays +=  $data->post_qual->days ; ?>

                                        @if($data->post_qual->days > 45)
                                            <strong class="red" tooltip="Delay">({{$data->post_qual->days - 45}})</strong>
                                        @endif

                                    @else
                                        @if($data->bid_open->closing_date != null)
                                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $next_date ); ?>
                                                {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                                {{$d}}
                                            @if($d > 45)
                                                <strong class="red" tooltip="Delay">({{$d - 45}})</strong>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($data->post_qual != null) {{$data->post_qual->remarks}} @endif
                            </td>
                            <td>
                                @if($data->post_qual != null) {{$data->post_qual->action}} @endif
                            </td>
                            <td>

                            </td>
                        </tr>
                    @endif
                    {{-- Post Qualification --}}



                @endif

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    6.
                    @else
                    10.
                    @endif
                    Prepare NOA
                    </td>
                    <td>
                        @if($data->noa_award_date != null)
                            <?php $noa_award_date = createCarbon('Y-m-d H:i:s',$data->noa_award_date)->format('Y-m-d'); ?>
                            <?php $noa_award_date = createCarbon('Y-m-d',$noa_award_date); ?>
                        <a target="_blank" href="{{route('procurements.noa.show', $data->noa_id)}}">
                            {{$noa_award_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>

                    @if($data->mode_of_procurement != 'public_bidding')
                    <td>2</td>
                    @else
                    <td>15</td>
                    @endif
                    <td>
                    @if(isset($canvass_start_date) && isset($noa_award_date))
                    <?php
                      $noa_days            =   $canvass_start_date->diffInDaysFiltered(function(\Carbon\Carbon $date)use ($h_lists) {
                          return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists);
                      }, $noa_award_date);
                    ?>
                        @if($data->noa_award_date)
                            {{ $noa_days }}
                            <?php $totalDays +=  $noa_days ; ?>

                            @if($noa_days > 2)
                                <strong class="red" tooltip="Delay">({{$noa_days - 2}})</strong>
                            @endif


                        @else
                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                                {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                {{$d}}

                                @if($d > 2)
                                    <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                                @endif
                        @endif
                    @else
                            {{ $data->noa_days }}
                            <?php $totalDays +=  $data->noa_days ; ?>

                            @if($data->noa_days > 15)
                                <strong class="red" tooltip="Delay">({{$data->noa_days - 15}})</strong>
                            @endif

                            <!--- @if($data->post_qual != null && $data->noa_days == null)
                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                                {{$d}}


                                @if($d > 15)
                                    <strong class="red" tooltip="Delay">({{$d - 15}})</strong>
                                @endif

                            @endif -->
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->noa_remarks}}</td>
                    <td>{{$data->noa_action}}</td>
                    <td>
                        @if($data->noa_award_date != null)
                        <a target="_blank"
                            href="{{route('procurements.noa.print', $data->noa_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    7.
                    @else
                    11.
                    @endif ISSUE NOA</td>
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
                                <strong class="red" tooltip="Delay">({{$data->noa_approved_days - 1}})</strong>
                            @endif


                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}


                            @if($d > 1)
                                <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->noa_approved_remarks}}</td>
                    <td>{{$data->noa_approved_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    8.
                    @else
                    12.
                    @endif Conforme NOA</td>
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
                                <strong class="red" tooltip="Delay">({{$data->noa_received_days - 1}})</strong>
                            @endif

                        @else


                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}


                            @if($d > 1)
                                <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->noa_received_remarks}}</td>
                    <td>{{$data->noa_received_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    9.
                    @else
                    13.
                    @endif Posting NOA to Philgeps</td>
                    <td>
                    <td></td>

                    <td>

                    </td>
                    <td> </td>
                    <td>{{$data->noa_received_remarks}}</td>
                    <td>{{$data->noa_received_action}}</td>
                    <td></td>
                </tr>

                @if($data->mode_of_procurement != 'public_bidding')
                <tr>
                    <td>10. Preparation of PO</td>
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
                    @if(isset($noa_philgeps_posting))
                        @if($data->po_create_date != null)
                            {{ $data->po_days }}
                            <?php $totalDays +=  $data->po_days ; ?>

                            @if($data->po_days > 2)
                                <strong class="red" tooltip="Delay">({{$data->po_days - 2}})</strong>
                            @endif

                        @else
                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>
                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}

                            @if($d > 2)
                                <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                            @endif

                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->po_remarks}}</td>
                    <td>{{$data->po_action}}</td>
                    <td>
                        @if($data->po_create_date != null)
                        <a target="_blank"
                            href="{{route('procurements.purchase-orders.print', $data->po_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @else

                <tr>
                    <td>14. Preparation of PO</td>
                    <td>
                        @if($data->po_create_date != null)
                        <?php $po_create_date = createCarbon('Y-m-d',$data->po_create_date); ?>
                        <a target="_blank" href="{{route('procurements.purchase-orders.show', $data->po_id)}}">
                            {{$po_create_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>10</td>
                    <td>
                    @if(isset($noa_philgeps_posting))
                        @if($data->po_create_date != null)
                            {{ $data->po_days }}
                            <?php $totalDays +=  $data->po_days ; ?>

                            @if($data->po_days > 10)
                                <strong class="red" tooltip="Delay">({{$data->po_days - 10}})</strong>
                            @endif

                        @else
                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>
                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}

                            @if($d > 10)
                                <strong class="red" tooltip="Delay">({{$d - 10}})</strong>
                            @endif

                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->po_remarks}}</td>
                    <td>{{$data->po_action}}</td>
                    <td>
                        @if($data->po_create_date != null)
                        <a target="_blank"
                            href="{{route('procurements.purchase-orders.print', $data->po_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endif

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    11.
                    @else
                    15.
                    @endif MFO Obligation</td>
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
                                <strong class="red" tooltip="Delay">({{$data->po_fund_days - 2}})</strong>
                            @endif
                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 2)
                                <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->po_funding_remarks}}</td>
                    <td>{{$data->po_funding_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    12.
                    @else
                    16.
                    @endif Issuance of CAF</td>
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
                                <strong class="red" tooltip="Delay">({{$data->po_mfo_days - 2}})</strong>
                            @endif

                        @else
                            @if(isset($funding_received_date) )

                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                                {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                {{$d}}
                                @if($d > 2)
                                    <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->po_mfo_remarks}}</td>
                    <td>{{$data->po_mfo_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    13.
                    @else
                    17.
                    @endif PO/WO/JO/CA Approval And NTP Preparation</td>
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
                                {{ $data->po_coa_days }}
                                <?php $totalDays +=  $data->po_coa_days ; ?>

                                @if($data->po_coa_days > 1)
                                    <strong class="red" tooltip="Delay">({{$data->po_coa_days - 1}})</strong>
                                @endif
                            @else

                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                                {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                {{$d}}
                                @if($d > 1)
                                    <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->po_coa_remarks}}</td>
                    <td>{{$data->po_coa_action}}</td>
                    <td></td>
                </tr>

                @if($data->mode_of_procurement != 'public_bidding')
                    <tr>
                        <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    14.
                    @else
                    18.
                    @endif Serving of NTP</td>
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
                                    <strong class="red" tooltip="Delay">({{$data->ntp_days - 1}})</strong>
                                @endif
                            @else

                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                                {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                {{$d}}
                                @if($d > 1)
                                    <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                                @endif
                            @endif
                        @endif
                        </td>
                    <td>{{ $totalDays }}</td>
                        <td>{{$data->ntp_remarks}}</td>
                        <td>{{$data->ntp_action}}</td>
                        <td>
                            @if($data->ntp_date != null)
                            <a target="_blank"
                                href="{{route('procurements.ntp.print', $data->ntp_id)}}" tooltip="Print">
                                <i class="nc-icon-mini tech_print"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    14.
                    @else
                    18.
                    @endif Serving of NTP</td>
                        <td>
                            @if($data->ntp_date)
                                <?php $ntp_dates = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->ntp_date)->format('Y-m-d'); ?>
                                <a target="_blank" href="{{route('procurements.ntp.show', $data->ntp_id)}}">
                                {{  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->ntp_date)->format('d F Y') }}
                                </a>
                            @endif
                        </td>
                        <td>7</td>
                        <td>
                        @if(isset($coa_approved_date) )
                            @if(isset($ntp_dates))
                                {{ $data->ntp_days }}
                                <?php $totalDays +=  $data->ntp_days ; ?>

                                @if($data->ntp_days > 7)
                                    <strong class="red" tooltip="Delay">({{$data->ntp_days - 7}})</strong>
                                @endif
                            @else

                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                                {{-- {{ ($d >= 7) ?  $d - 7 : $d }} --}}
                                {{$d}}
                                @if($d > 7)
                                    <strong class="red" tooltip="Delay">({{$d - 7}})</strong>
                                @endif
                            @endif
                        @endif
                        </td>
                    <td>{{ $totalDays }}</td>
                        <td>{{$data->ntp_remarks}}</td>
                        <td>{{$data->ntp_action}}</td>
                        <td>
                            @if($data->ntp_date != null)
                            <a target="_blank"
                                href="{{route('procurements.ntp.print', $data->ntp_id)}}" tooltip="Print">
                                <i class="nc-icon-mini tech_print"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                @endif

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    15.
                    @else
                    19.
                    @endif Conforme of NTP</td>
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
                    @if($data->ntp_date)
                        @if($data->ntp_award_date != null)
                            {{ $data->ntp_accepted_days }}
                            <?php $totalDays +=  $data->ntp_accepted_days ; ?>

                            @if($data->ntp_accepted_days > 1)
                                <strong class="red" tooltip="Delay">({{$data->ntp_accepted_days - 1}})</strong>
                            @endif
                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>
                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 1)
                                <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->ntp_accepted_remarks}}</td>
                    <td>{{$data->ntp_accepted_action}}</td>
                    <td> </td>
                </tr>


                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    16.
                    @else
                    20.
                    @endif Philgeps Posting of NTP</td>
                    <td>
                    </td>
                    <td></td>
                    <td></td>
                    <td>

                    </td>
                    <td>{{$data->ntp_accepted_remarks}}</td>
                    <td>{{$data->ntp_accepted_action}}</td>
                    <td> </td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    17.
                    @else
                    21.
                    @endif Issuance of Notice of Delivery </td>
                    <td>
                        @if($data->dr_date != null)
                        <?php $dr_date = createCarbon('Y-m-d',$data->dr_date); ?>
                        <a target="_blank" href="{{route('procurements.delivery-orders.show', $data->dr_id)}}">
                            {{$dr_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>{{$data->delivery_terms}}</td>
                    <td>
                        @if($data->dr_date != null)
                            {{ $data->dr_days }}
                            <?php $totalDays +=  $data->dr_days ; ?>

                            @if($data->dr_days > $data->delivery_terms)
                                <strong class="red" tooltip="Delay">({{$data->dr_days - 2}})</strong>
                            @endif

                        @else
                            @if($data->ntp_award_date)
                              <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                              {{$d}}
                              @if($d > $data->delivery_terms)
                                  <strong class="red" tooltip="Delay">({{$d - $data->delivery_terms}})</strong>
                              @endif
                            @endif
                        @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->dr_delivery_remarks}}</td>
                    <td>{{$data->dr_delivery_action}}</td>
                    <td> </td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    18.
                    @else
                    22.
                    @endif Delivery of items</td>
                    <td>
                        @if($data->dr_date != null && $data->delivery_date != null)
                        <?php $delivery_date = createCarbon('Y-m-d',$data->delivery_date); ?>
                        <a target="_blank" href="{{route('procurements.delivery-orders.show', $data->dr_id)}}">
                            {{$delivery_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>{{$data->delivery_terms}}</td>
                    <td>
                        @if($data->delivery_date != null)
                            {{ $data->dr_delivery_days }}
                            <?php $totalDays +=  $data->dr_delivery_days ; ?>

                            @if($data->dr_delivery_days > $data->delivery_terms)
                                <strong class="red" tooltip="Delay">({{$data->dr_delivery_days - $data->delivery_terms}})</strong>
                            @endif

                        @else

                          @if($data->dr_date != null)
                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > $data->delivery_terms)
                                <strong class="red" tooltip="Delay">({{$d - $data->delivery_terms}})</strong>
                            @endif
                          @endif
                        @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->dr_delivery_remarks}}</td>
                    <td>{{$data->dr_delivery_action}}</td>
                    <td> </td>
                </tr>
                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    19.
                    @else
                    23.
                    @endif Notification of delivery to COA</td>
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
                        @if($data->dr_coa_date != null)
                            {{ $data->dr_dr_coa_days }}
                            <?php $totalDays +=  $data->dr_dr_coa_days ; ?>

                            @if($data->dr_dr_coa_days > 1)
                                <strong class="red" tooltip="Delay">({{$data->dr_dr_coa_days - 1}})</strong>
                            @endif

                        @else
                            @if($data->delivery_date != null)
                                <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                                {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                                {{$d}}
                                @if($d > 1)
                                    <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->dr_dr_coa_remarks}}</td>
                    <td>{{$data->dr_dr_coa_action}}</td>
                    <td> </td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    20.
                    @else
                    24.
                    @endif Conduct of TIAC</td>
                    <td>
                        @if($data->dr_inspection != null)
                        <?php $dr_inspection = createCarbon('Y-m-d',$data->dr_inspection); ?>
                        <a target="_blank" href="{{route('procurements.inspection-and-acceptance.show', $data->tiac_id)}}">
                            {{$dr_inspection->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if(isset($dr_coa_date))
                        @if($data->dr_inspection != null)
                            {{ $data->tiac_days }}
                            <?php $totalDays +=  $data->tiac_days ; ?>

                            @if($data->tiac_days > 2)
                                <strong class="red" tooltip="Delay">({{$data->tiac_days - 2}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 2)
                                <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->tiac_remarks}}</td>
                    <td>{{$data->tiac_action}}</td>
                    <td>
                        @if($data->dr_inspection != null)
                        <a target="_blank"
                            href="{{route('procurements.inspection-and-acceptance.print', $data->tiac_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    21.
                    @else
                    25.
                    @endif Inspection and Acceptance Report</td>
                    <td>
                        @if($data->iar_accepted_date != null)
                        <?php $iar_accepted_date = createCarbon('Y-m-d',$data->iar_accepted_date); ?>
                        <a target="_blank" href="{{route('procurements.inspection-and-acceptance.show', $data->tiac_id)}}">
                            {{$iar_accepted_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if(isset($dr_inspection))
                        @if($data->iar_accepted_date != null)
                            {{ $data->tiac_accept_days }}
                            <?php $totalDays +=  $data->tiac_accept_days ; ?>

                            @if($data->tiac_accept_days > 2)
                                <strong class="red" tooltip="Delay">({{$data->tiac_accept_days - 2}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 2)
                                <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->tiac_accept_remarks}}</td>
                    <td>{{$data->tiac_accept_action}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    22.
                    @else
                    26.
                    @endif Delivered Items and Inspection Report</td>
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
                                <strong class="red" tooltip="Delay">({{$data->diir_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 1)
                                <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->diir_remarks}}</td>
                    <td>{{$data->diir_action}}</td>
                    <td>
                        @if($data->di_start != null)
                        <a target="_blank"
                            href="{{route('procurements.delivered-inspections.print', $data->diir_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    23.
                    @else
                    27.
                    @endif Preparation of DV</td>
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
                                <strong class="red" tooltip="Delay">({{$data->vou_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 1)
                                <strong class="red" tooltip="Delay">({{$d - 1}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_remarks}}</td>
                    <td>{{$data->vou_action}}</td>
                    <td>
                        @if($data->v_transaction_date != null)
                        <a target="_blank"
                            href="{{route('procurements.vouchers.print', $data->vou_id)}}" tooltip="Print">
                            <i class="nc-icon-mini tech_print"></i>
                        </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    24.
                    @else
                    28.
                    @endif Sign box `A` of DV </td>
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
                    @if(isset($v_transaction_date))
                        @if($data->certify_date != null)

                            {{ $data->vou_certify_days }}
                            <?php $totalDays +=  $data->vou_certify_days ; ?>

                            @if($data->vou_certify_days > 1)
                                <strong class="red" tooltip="Delay">({{$data->vou_certify_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_certify_remarks}}</td>
                    <td>{{$data->vou_certify_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    25.
                    @else
                    29.
                    @endif Accomplish box `B` of DV and Sign box `C` of DV </td>
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
                                <strong class="red" tooltip="Delay">({{$data->vou_jev_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                        @endif
                    @endif
                    </td>
                    <td>{{$data->vou_jev_remarks}}</td>
                    <td>{{$data->vou_jev_action}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    26.
                    @else
                    30.
                    @endif Sign box `D` of DV</td>
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
                                <strong class="red" tooltip="Delay">({{$data->vou_approved_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_approved_remarks}}</td>
                    <td>{{$data->vou_approved_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    27.
                    @else
                    31.
                    @endif Pre-audit</td>
                    <td>
                        @if($data->preaudit_date != null)
                        <?php $preaudit_date = createCarbon('Y-m-d',$data->preaudit_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$preaudit_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if(isset($vou_approval_date))
                        @if($data->preaudit_date != null)
                            {{ $data->vou_preaudit_days }}
                            <?php $totalDays +=  $data->vou_preaudit_days ; ?>

                            @if($data->vou_preaudit_days > 2)
                                <strong class="red" tooltip="Delay">({{$data->vou_preaudit_days - 2}})</strong>
                            @endif


                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 2)
                                <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_preaudit_remarks}}</td>
                    <td>{{$data->vou_preaudit_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    28.
                    @else
                    32.
                    @endif Prepare and Sign LDDAP-ADA</td>
                    <td>
                        @if($data->vou_prepare_cheque_date != null)
                        <?php $vou_prepare_cheque_date = createCarbon('Y-m-d',$data->vou_prepare_cheque_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$vou_prepare_cheque_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>2</td>
                    <td>
                    @if(isset($preaudit_date))
                        @if($data->vou_prepare_cheque_date != null)
                            {{ $data->vou_preaudit_days }}
                            <?php $totalDays +=  $data->vou_preaudit_days ; ?>

                            @if($data->vou_preaudit_days > 2)
                                <strong class="red" tooltip="Delay">({{$data->vou_preaudit_days - 2}})</strong>
                            @endif


                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                            @if($d > 2)
                                <strong class="red" tooltip="Delay">({{$d - 2}})</strong>
                            @endif
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_preaudit_remarks}}</td>
                    <td>{{$data->vou_preaudit_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    29.
                    @else
                    33.
                    @endif Sign LDDAP-ADA or Prepare Cheque</td>
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
                    @if(isset($vou_prepare_cheque_date) )
                        @if($data->vou_release != null)
                            {{ $data->vou_released_days }}
                            <?php $totalDays +=  $data->vou_released_days ; ?>

                            @if($data->vou_released_days > 1)
                                <strong class="red" tooltip="Delay">({{$data->vou_released_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_released_remarks}}</td>
                    <td>{{$data->vou_released_action}}</td>
                    <td></td>
                </tr>


                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    30.
                    @else
                    34.
                    @endif Sign LDDAP-ADA or Counter-Sign Cheque</td>
                    <td>
                        @if($data->vou_counter_sign_date != null)
                        <?php $vou_counter_sign_date = createCarbon('Y-m-d',$data->vou_counter_sign_date); ?>
                        <a target="_blank" href="{{route('procurements.vouchers.show', $data->vou_id)}}">
                            {{$vou_counter_sign_date->format('d F Y')}}
                        </a>
                        @endif
                    </td>
                    <td>1</td>
                    <td>
                    @if(isset($vou_release) )
                        @if($data->vou_counter_sign_date != null)
                            {{ $data->vou_counter_sign_days }}
                            <?php $totalDays +=  $data->vou_counter_sign_days ; ?>

                            @if($data->vou_counter_sign_days > 1)
                                <strong class="red" tooltip="Delay">({{$data->vou_counter_sign_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_released_remarks}}</td>
                    <td>{{$data->vou_released_action}}</td>
                    <td></td>
                </tr>


                <tr>
                    <td>
                    @if($data->mode_of_procurement != 'public_bidding')
                    31.
                    @else
                    35.
                    @endif Receipt of Cheques and Issue Official Receipt</td>
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
                                <strong class="red" tooltip="Delay">({{$data->vou_received_days - 1}})</strong>
                            @endif

                        @else

                            <?php  $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, createCarbon('Y-m-d H:i:s',$next_date) ); ?>

                            {{-- {{ ($d >= 1) ?  $d - 1 : $d }} --}}
                            {{$d}}
                        @endif
                    @endif
                    </td>
                    <td>{{ $totalDays }}</td>
                    <td>{{$data->vou_received_remarks}}</td>
                    <td>{{$data->vou_received_action}}</td>
                    <td></td>
                </tr>

                <tr>
                    <td>Total</td>
                    <td></td>
                    <td>Working Days</td>
                    <?php $total = 0;?>
                    @if(isset($vou_approval_date))
                    <?php  $total =  $upr_created->diffInDaysFiltered(function (\Carbon\Carbon $date) use ($h_lists) {return $date->isWeekday() && !in_array($date->format('Y-m-d'), $h_lists); }, $vou_approval_date ); ?>
                    @endif
                    {{-- <td>
                        @if($total > 0)
                            {{$total - 1}}
                        @else
                            {{$total}}
                        @endif
                    </td> --}}
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

$('#printme').on('click', function(e){
e.preventDefault();
var id = "{{$data->id}}";
window.open("/timelines/print/"+id);
});
var datapanel = $('.data-panel').width()
$(window).scroll(function(e) {
    var $tableBodyCell = $('tbody tr:first td');
    var $headerCell = $('thead tr th');

    $tableBodyCell.each(function(index){
         $headerCell.eq(index).width($(this).width());
         console.log($(this).width())
    });

    // width1 = $( "tbody tr td:first-child" ).width()
    // var rowHeight = $('thead tr').height();
    var offset = $('.table').offset().left;
    var offsetTop = $('.table').offset().top;
    $('.data-panel').css('position','fixed');
    $('.data-panel').css('top', '100px');
    $('.data-panel').css('width', datapanel);
    $('thead').css('position','fixed');
    $('thead').css('top','150px');
    $('thead').css('height','100px');
    // $('tbody').css('margin-top', '200px');

    if($(window).scrollTop() == 0)
    {
      $('thead').css('position','inherit');
      $('thead').css('height','auto');
      $('tbody').css('top', 'auto');
      $('.data-panel').css('position','inherit');
    }
})
</script>
@stop