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

                    <td>{{ucfirst($data->rfq_status)}}</td>

                    <td>{{$data->rfq_completed_at}}</td>

                    <td>
                    @if($data->rfq_completed_at && $data->rfq_completed_at)
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at); ?>
                        <?php $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->date_prepared); ?>

                        {{ $dt->diffInDays($upr_create) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>PhilGeps Posting</td>

                    <td>Completed</td>

                    <td>{{$data->pp_completed_at}}</td>

                    <td>
                    @if($data->pp_completed_at && $data->rfq_completed_at)
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->pp_completed_at); ?>
                        <?php $upr_create = Carbon\Carbon::createFromFormat('Y-m-d  H:i:s', $data->rfq_completed_at); ?>

                        {{ $dt->diffInDays($upr_create) }}
                    @endif
                    </td>
                </tr>

                <tr>
                    <td>Invitation</td>

                    <td>Completed</td>

                    <td>{{$data->pp_completed_at}}</td>

                    <td>
                    @if($data->ispq_transaction_date && $data->rfq_completed_at)
                        {{-- Assigning dates to get day interval--}}
                        <?php $dt = Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date); ?>
                        <?php $upr_create = Carbon\Carbon::createFromFormat('Y-m-d  H:i:s', $data->rfq_completed_at); ?>

                        {{ $dt->diffInDays($upr_create) }}
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