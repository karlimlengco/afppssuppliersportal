@section('title')
Overview
@stop

@section('contents')
<div class="table-scroll alternative">
    <table class="table table--with-border">

        <thead>
            <tr>
                <th>Ref #</th>
                <th>UPR</th>
                <th>Project</th>
                <th>ABC</th>
                <th>Document Acceptance</th>
                <th>ITB</th>
                <th>PRE Bid</th>
                <th>Bid Opening</th>
                <th>Post Qual</th>
                <th>RFQ</th>
                <th>RFQ Closed</th>
                <th>PhilGeps</th>
                <th>ISPQ</th>
                <th>Canvass</th>
                <th>NOA</th>
                <th>NOAA</th>
                <th>PO</th>
                <th>MFO OB</th>
                <th>ACCTG OB</th>
                <th>MFO Received</th>
                <th>ACCTG Received</th>
                <th>COA Approved</th>
                <th>NTP</th>
                <th>NTPA</th>
                <th>Delivery</th>
                <th>TIAC</th>
                <th>DIIR</th>
                <th>Voucher</th>
                <th>End</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $data)
                <tr>
                    <td>{{$data->ref_number}}</td>
                    <td>{{$data->upr_number}}</td>
                    <td>{{$data->project_name}}</td>
                    <td>{{formatPrice($data->total_amount)}}</td>
                    <td>{{($data->document_accept) ?createCarbon( 'Y-m-d', $data->document_accept->transaction_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->itb) ?createCarbon( 'Y-m-d', $data->itb->transaction_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->bid_conference) ?createCarbon( 'Y-m-d', $data->bid_conference->transaction_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->bid_open) ?createCarbon( 'Y-m-d', $data->bid_open->transaction_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->post_qual) ?createCarbon( 'Y-m-d', $data->post_qual->transaction_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->rfq) ? $data->rfq->transaction_date->format('d F Y') : ""}}</td>
                    <td>{{($data->rfq && $data->rfq->completed_at) ? $data->rfq->completed_at->format('d F Y') : ""}}</td>
                    <td>{{($data->philgeps) ? createCarbon( 'Y-m-d', $data->philgeps->transaction_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->invitations) ? createCarbon( 'Y-m-d', $data->invitations->canvassing_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->invitations) ? createCarbon( 'Y-m-d', $data->invitations->canvassing_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->noa) ? createCarbon( 'Y-m-d H:i:s', $data->noa->awarded_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->noa) ? createCarbon( 'Y-m-d', $data->noa->award_accepted_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->purchase_order) ? createCarbon( 'Y-m-d', $data->purchase_order->purchase_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->purchase_order && $data->purchase_order->funding_released_date) ? createCarbon( 'Y-m-d', $data->purchase_order->funding_released_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->purchase_order && $data->purchase_order->mfo_received_date) ? createCarbon( 'Y-m-d', $data->purchase_order->mfo_received_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->purchase_order && $data->purchase_order->funding_received_date) ? createCarbon( 'Y-m-d', $data->purchase_order->funding_received_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->purchase_order && $data->purchase_order->mfo_released_date) ? createCarbon( 'Y-m-d', $data->purchase_order->mfo_released_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->purchase_order && $data->purchase_order->coa_approved_date) ? createCarbon( 'Y-m-d', $data->purchase_order->coa_approved_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->ntp) ? createCarbon( 'Y-m-d H:i:s', $data->ntp->prepared_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->ntp && $data->ntp->award_accepted_date) ? createCarbon( 'Y-m-d', $data->ntp->award_accepted_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->delivery_order) ? createCarbon( 'Y-m-d', $data->delivery_order->delivery_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->inspections) ? createCarbon( 'Y-m-d', $data->inspections->inspection_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->diir) ? createCarbon( 'Y-m-d', $data->diir->start_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->voucher) ? createCarbon( 'Y-m-d', $data->voucher->transaction_date)->format('d F Y') : ""}}</td>
                    <td>{{($data->voucher && $data->voucher->payment_received_date) ? createCarbon( 'Y-m-d', $data->voucher->payment_received_date)->format('d F Y') : ""}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@stop