<div class="twelve columns align-right utility utility--align-right">
    @if($data->mode_of_procurement != 'public_bidding')
        @if($data->status == 'upr_processing')
         {{--    <span >Process &amp; Create RFQ</span>
            <a href="#" class="button" id="process-button" tooltip="Next Stage">
                <i class="nc-icon-mini arrows-1_bold-right"></i>
            </a> --}}
            <span >Create Invitation</span>
            <a href="#" class="button" id="invitation-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'Philgeps Approved')
            {{-- <a href="#" class="button__options__item" id="invitation-button">Create Invitation</a> --}}
         {{--    <a href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span> --}}

            <span >Create RFQ</span>
            <a href="#" class="button" id="process-button" tooltip="Next Stage">
                <i class="nc-icon-mini arrows-1_bold-right"></i>
            </a>
        @endif
        @if($data->status == 'Processing RFQ')
            @if($data->philgeps)
            <a href="{{route('procurements.philgeps-posting.show', $data->philgeps->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>
            @endif
            <span >View RFQ</span>
            <a href="{{route('procurements.blank-rfq.show',$data->rfq->id)}}" class="button" tooltip="Next Stage">
                <i class="nc-icon-mini arrows-1_bold-right"></i>
            </a>
        @endif
        @if( $data->status == 'Philgeps Need Repost' || $data->status == 'Failed Bid')


            {{-- <a href="#" class="button__options__item" id="philgeps-posting-button">PhilGeps Posting</a> --}}
            <span >PhilGeps Posting</span>
            <a href="#" class="button" id="philgeps-posting-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'Invitation Created')
            @if($data->invitations)
            <a href="{{route('procurements.ispq.edit', $data->invitations->ispq_id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            @endif
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>
            @if($data->total_amount > 50000)
                <span >PhilGeps Posting</span>
                <a href="#" class="button" id="philgeps-posting-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
            @else
                <span >Create RFQ</span>
                <a href="#" class="button" id="process-button" tooltip="Next Stage">
                    <i class="nc-icon-mini arrows-1_bold-right"></i>
                </a>
            @endif

            {{-- <a href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" class="button"  tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a> --}}
        @endif
        {{-- @if($data->status == 'Invitation Created' || $data->status == 'Philgeps Need Repost' || $data->status == 'Failed Bid')
            <a href="{{route('procurements.ispq.edit', $data->invitations->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

             <span >PhilGeps Posting</span>
            <a href="#" class="button" id="philgeps-posting-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif --}}
        @if($data->status == 'Close RFQ')
            <a href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

            {{-- <a href="#" id="open_canvass-button" class="button__options__item">Open Canvass</a> --}}
            <span >Open Canvass</span>
            <a href="#" class="button" id="open_canvass-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'Open Canvass')
            <a href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

            <span >View Canvass</span>
            <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
            {{-- <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" class="button__options__item">View Canvass</a> --}}
        @endif
        {{-- Process --}}

    @else
        @if($data->status == 'upr_processing')
        {{-- <a class="button__options__item"  href="{{route('biddings.document-acceptance.create-by-rfq', $data->id)}}">Document Acceptance</a> --}}
            <span >Document Acceptance</span>
            <a href="{{route('biddings.document-acceptance.create-by-rfq', $data->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'Document Accepted'  || $data->status == 'Failed Post Qualification'|| $data->status == 'Failed SOBE'|| $data->status == 'Failed Pre Bid'|| $data->status == 'Disqualification of Proponent')
            {{-- <a class="button__options__item" id="itb-button" href="#">Invitation To Bid</a> --}}

            <a href="{{route('biddings.document-acceptance.show', $data->document_accept->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

            <span >Pre Proc</span>
            <a href="#" class="button" id="preproc-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'PreProc Conference')
            {{-- <a class="button__options__item" id="itb-button" href="#">Invitation To Bid</a> --}}

            <a href="{{route('biddings.preproc.show', $data->preproc->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

            <span >Invitation To Bid</span>
            <a href="#" class="button" id="itb-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'ITB Created' || $data->status == 'Philgeps Need Repost')
            {{-- <a class="button__options__item" id="biddings-philgeps-posting-button" href="#">PhilGeps Posting</a> --}}

            <a href="{{route('biddings.itb.show', $data->itb->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

            <span >PhilGeps Posting</span>
            <a href="#" class="button" id="biddings-philgeps-posting-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'Philgeps Approved')
            <a href="{{route('procurements.philgeps-posting.show', $data->philgeps->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-ico  n-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

            <span >Pre-Bid Conference</span>
            <a href="{{route('biddings.pre-bids.create-by-upr', $data->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'Pre Bid Conference')

            <a href="{{route('biddings.pre-bids.show', $data->bid_conference->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

            <span >SOBE</span>
            <a href="#" class="button" id="open-bid-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
            {{-- <a class="button__options__item" id="open-bid-button" href="#">SOBE</a> --}}
        @endif
        @if($data->status == 'SOBE OPEN')

            <a href="{{route('biddings.pre-bids.show', $data->bid_conference->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>
            {{-- <a class="button__options__item" id="post-qual-button" href="#">Post Qualification</a> --}}

            <span >SOBE</span>
            <a href="{{route('biddings.bid-openings.show', $data->bid_open->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'SOBE Closed')

            <a href="{{route('biddings.bid-openings.show', $data->bid_open->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>
            {{-- <a class="button__options__item" id="post-qual-button" href="#">Post Qualification</a> --}}

            <span >Post Qualification</span>
            <a href="#" class="button" id="post-qual-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
        @if($data->status == 'Post Qualification')

            <a href="{{route('biddings.bid-openings.show', $data->bid_open->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>
            {{-- <a class="button__options__item" id="post-qual-button" href="#">Post Qualification</a> --}}

            <span >Post Qualification</span>
            <a href="{{route('biddings.post-qualifications.show', $data->post_qual->id)}}" class="button"  tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
    @endif

    @if(strpos($data->status, 'Awarded To') !== false || $data->status == 'Approved NOA')

        @if($data->mode_of_procurement == 'public_bidding')
            <a href="{{route('biddings.post-qualifications.show', $data->post_qual->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>
        @else
            <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
            <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>
        @endif

        <span >View NOA</span>
        <a href="{{route('procurements.noa.show', $data->noa->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a href="{{route('procurements.noa.show', $data->noa->id)}}" class="button__options__item">View NOA</a> --}}
    @endif

    @if($data->status == 'NOA Received')
        <span >Create PO</span>
        <a href="{{route('procurements.purchase-orders.rfq', $data->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a href="{{route('procurements.purchase-orders.rfq', $data->id)}}" class="button__options__item">Create PO</a> --}}
    @endif

    @if($data->status == 'PO Created' || $data->status == 'Contract Created' || $data->status == 'PO Funding Approved' || $data->status == 'PO MFO Approved')
        {{-- <a href="{{route('procurements.purchase-orders.show', $data->purchase_order->id)}}" class="button__options__item">View PO</a> --}}

        <span >View PO</span>
        <a href="{{route('procurements.purchase-orders.show', $data->purchase_order->id)}}" class="button"  tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
    @endif

    @if($data->status == 'PO Approved')
        {{-- <a href="#" class="button__options__item" id="ntp-button"> Notice To Proceed</a> --}}

        <a href="{{route('procurements.purchase-orders.show', $data->purchase_order->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

        <span >Prepare NTP</span>
        <a href="#" class="button" id="ntp-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
    @endif

    @if($data->status == 'NTP Created')
        {{-- <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" class="button__options__item"> View Notice To Proceed</a> --}}

        <a href="{{route('procurements.purchase-orders.show', $data->purchase_order->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

        <span >View Notice To Proceed</span>
        <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" class="button"  tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
    @endif

    @if($data->status == 'NTP Accepted')

        <a href="{{route('procurements.purchase-orders.show', $data->purchase_order->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

        <span >Create Notice Of Delivery</span>
        <a href="#" class="button" id="create-delivery-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a class="button__options__item" id="create-delivery-button" href="#">Create Notice Of Delivery</a> --}}
    @endif

    @if($data->status == 'NOD Created' || $data->status == 'Delivery Received' || $data->status == 'Delivery Incomplete')

        <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

        <span >View Notice Of Delivery</span>
        <a href="{{route('procurements.delivery-orders.show', $data->delivery_order->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
    {{-- <a class="button__options__item"  href="{{route('procurements.delivery-orders.show', $data->delivery_order->id)}}">View Notice Of Delivery</a> --}}
    @endif

    @if($data->status == 'Delivery Partial')

        <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" tooltip="Previous Stage" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Prev Stage</span>

        <span >View Notice Of Delivery</span>
        <a href="{{route('procurements.delivery-orders.lists', $data->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
    {{-- <a class="button__options__item"  href="{{route('procurements.delivery-orders.show', $data->delivery_order->id)}}">View Notice Of Delivery</a> --}}
    @endif
    @if($data->status == 'Complete COA Delivery')

        <span >Technical Inspection</span>
        <a href="{{route('procurements.inspection-and-acceptance.create-from-delivery',$data->delivery_order->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a class="button__options__item" href="{{route('procurements.inspection-and-acceptance.create-from-delivery',$data->delivery_order->id)}}">Technical Inspection</a> --}}
    @endif
    @if($data->status == 'Inspection Started')

        <span >View Technical Inspection</span>
        <a href="{{route('procurements.inspection-and-acceptance.show',$data->inspections->id)}}" class="button"  tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a class="button__options__item" href="{{route('procurements.inspection-and-acceptance.show',$data->inspections->id)}}"> View Technical Inspection</a> --}}
    @endif
    @if($data->status == 'Inspection Accepted')
        <span >Delivered Items</span>
        <a href="{{route('procurements.delivery-orders.store-by-dr',$data->delivery_order->id)}}" class="button"  tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a class="button__options__item" href="{{route('procurements.delivery-orders.store-by-dr',$data->delivery_order->id)}}">Delivered Items</a> --}}
    @endif
    @if( $data->status == 'DIIR Created' || $data->status == 'DIIR Started')

        <span >Delivered Items</span>
        <a href="{{route('procurements.delivered-inspections.show',$data->diir->id)}}" class="button"  tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a class="button__options__item" href="{{route('procurements.delivered-inspections.show',$data->diir->id)}}">Delivered Items</a> --}}
    @endif
    @if( $data->status == 'DIIR Closed')

        <span >Create Voucher</span>
        <a href="#" class="button" id="voucher-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        {{-- <a href="#" id="voucher-button" class="button__options__item">Create Voucher</a> --}}
    @endif
    @if( $data->status == 'Voucher Created' || $data->status == 'Voucher Preaudit' || $data->status == 'Voucher Certify'|| $data->status == 'Voucher Journal Entry'|| $data->status == 'Voucher Approved'|| $data->status == 'Voucher Released')
       {{-- <a href="{{route('procurements.vouchers.show', $data->voucher->id)}}" class="button__options__item">View Voucher</a> --}}

        <span >View Voucher</span>
        <a href="{{route('procurements.vouchers.show', $data->voucher->id)}}" class="button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
    @endif
</div>