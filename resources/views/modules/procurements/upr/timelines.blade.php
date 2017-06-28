@section('title')
Unit Purchase Request
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
        {{-- <h3>Unit Purchase Request</h3> --}}
    </div>
    <div class="twelve columns utility utility--align-right" >

        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <table class="table" >
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Day/s</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>UPR</td>
                    <td>{{$data->state}}</td>
                    <td>{{$data->date_prepared}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>RFQ</td>

                    @if($data->rfq_completed_at && $data->rfq_completed_at)
                    <td>{{ ucfirst($data->rfq_status)}}</td>

                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at)->format('Y-m-d')}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at); ?>
                        <?php $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->date_prepared); ?>

                        {{ $dt->diffInDays($upr_create) }}
                    </td>
                    @endif
                    @if($data->date_prepared and !$data->rfq_completed_at)

                        <?php $dt = Carbon\Carbon::now(); ?>
                        <?php $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->date_prepared); ?>
                        <?php $days =   $dt->diffInDays($upr_create); ?>
                        @if($days >= 1)
                            <td>Delayed</td>
                        @else
                            <td></td>
                        @endif

                        <td></td>
                        <td>{{ $days }}</td>
                    @endif
                </tr>

                <tr>
                    <td>PhilGeps Posting</td>
                    @if($data->pp_completed_at && $data->rfq_completed_at)

                    <td>Completed</td>

                    <td>{{$data->pp_completed_at}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->pp_completed_at); ?>
                        <?php $upr_create = Carbon\Carbon::createFromFormat('Y-m-d  H:i:s', $data->rfq_completed_at); ?>

                        {{ ($dt->diffInDays($upr_create) == 0) ? "1" : $dt->diffInDays($upr_create) }}
                    </td>
                    @endif
                </tr>

                <tr>
                    <td>Invitation</td>
                    @if($data->ispq_transaction_date && $data->rfq_completed_at)

                    <td>Completed</td>

                    <td>{{$data->pp_completed_at}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date); ?>
                        <?php $upr_create = Carbon\Carbon::createFromFormat('Y-m-d  H:i:s', $data->rfq_completed_at); ?>

                        {{ ($dt->diffInDays($upr_create) == 0) ? "1" : $dt->diffInDays($upr_create) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Canvassing</td>

                    @if($data->canvass_start_date)
                    <td>Completed</td>

                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->canvass_start_date)->format('Y-m-d')}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->canvass_start_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Release NOA</td>

                    @if($data->noa_award_date )
                    <td>Completed</td>

                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date)->format('Y-m-d') }}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->canvass_start_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Approved NOA</td>

                    @if($data->noa_approved_date )
                    <td>Completed</td>

                    <td>{{$data->noa_approved_date}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Accepted NOA</td>
                    @if($data->noa_award_accepted_date )

                    <td>Completed</td>

                    <td>{{$data->noa_award_accepted_date}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Preparing PO</td>

                    @if($data->po_create_date )
                    <td>Completed</td>

                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->po_create_date)->format('Y-m-d') }}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->po_create_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Funding PO</td>
                    @if($data->mfo_released_date )

                    <td>Completed</td>

                    <td>{{$data->mfo_released_date}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_released_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->po_create_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Issuance of Certificate</td>
                    @if($data->pcco_released_date )

                    <td>Completed</td>

                    <td>{{$data->pcco_released_date}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->pcco_released_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_released_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>PO Approval</td>

                    @if($data->coa_approved_date )
                    <td>Completed</td>

                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->coa_approved_date)->format('Y-m-d')}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->coa_approved_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->pcco_released_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Prepared NTP</td>
                    @if($data->ntp_date )

                    <td>Completed</td>

                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date)->format('Y-m-d')}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->coa_approved_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Serving NTP</td>

                    @if($data->ntp_award_date )
                    <td>Completed</td>

                    <td>{{$data->ntp_award_date}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->ntp_award_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Delivery of Items</td>

                    @if($data->delivery_date )
                    <td>Completed</td>

                    <td>{{$data->delivery_date}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->dr_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Delivery To COA</td>

                    @if($data->dr_coa_date )
                    <td>Completed</td>

                    <td>{{$data->dr_coa_date}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Conduct Inspection</td>

                    @if($data->dr_inspection )
                    <td>Completed</td>

                    <td>{{$data->dr_inspection}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>
                <tr>
                    <td>Conduct Inspection of Delivered Items</td>

                    @if($data->di_start )
                    <td>Completed</td>

                    <td>{{$data->di_start}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Prepare Certificate of Inspection</td>

                    @if($data->di_close )
                    <td>Completed</td>

                    <td>{{$data->di_close}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Preparation of Voucher</td>
                    @if($data->vou_start )

                    <td>Completed</td>

                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start)->format('Y-m-d') }}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Release Payment</td>

                    @if($data->vou_release )
                    <td>Completed</td>

                    <td>{{$data->vou_release}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Received Payment</td>
                    @if($data->vou_received )

                    <td>Completed</td>

                    <td>{{$data->vou_received}}</td>

                    <td>
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_received); ?>
                        <?php $isqp = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release); ?>

                        {{ ($dt->diffInDays($isqp) == 0) ? "1" : $dt->diffInDays($isqp) }}
                    @endif
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

</script>
@stop