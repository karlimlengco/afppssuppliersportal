<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

        <style type="text/css">
            body{
                margin:0;
            }
        </style>
    </head>

    <body>

        <div class="printable-form-wrapper">

            <div class="printable-form">
                    <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>

                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span>
                        <span class="printable-form__letterhead__details">
                            @if($center != null)
                            <strong>{{$center->name}}</strong><br>
                            Armed Forces of the Philippines Procurement Service<br>
                            {{$center->address}}
                            @endif
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">Invitation to Submit a Quotation / Proposal</span>
                    <!-- content -->
                    <p>{{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>1. The Armed Forces of the Philippines through the 302ND  Canvass/Contracting Committee invites PhilGEPS registered suppliers to submit quotation/proposal for the following list of procurement with corresponding Approved Budget for the Contract (ABC).</p>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-center" width="10%"><strong>ITEM NO</strong></td>
                            <td class="align-center" width="20%"><strong>UPR NO</strong></td>
                            <td class="align-center" width="20%"><strong>RFQ NO</strong></td>
                            <td class="align-center" width="10%"><strong>MP</strong></td>
                            <td class="align-center" width="30%"><strong>ITEM DESCRIPTION</strong></td>
                            <td class="align-center" width="10%"><strong>ABC</strong></td>
                            <td class="align-center" width="20%"><strong>CANVASSING DATE/TIME</strong></td>
                        </tr>
                        @foreach($data['quotations'] as $key => $quotation)
                            <tr>
                                <td class="align-center"  style="text-align:center">{{$key+1}}</td>
                                <td class="align-center" >{{$quotation->upr_number}}</td>
                                <td class="align-center" >{{$quotation->rfq_number}}</td>
                                <td class="align-center" >@if($quotation->upr) @if($quotation->upr->modes) {{$quotation->upr->modes->name}} @endif @endif</td>
                                <td class="align-left" >{{$quotation->description}}</td>
                                <td class="align-right" >{{formatPrice($quotation->total_amount)}}</td>
                                <td class="align-center" >{{$quotation->canvassing_date}} {{$quotation->canvassing_time}}</td>
                            </tr>
                        @endforeach
                    </table>
                    <p>2. Interested Suppliers may obtain further information from Canvass Contracting Committee Secretariat at the office of 302ND Contracting Office,  Naval Base Pascual Ledesma Fort San Felipe, Cavite City Philippines for the issuance of Request for Quotation (RFQ) from <strong>8:00 AM - 5:00 PM Monday to Friday excluding Saturdays, Sundays and Holidays</strong>.</p>
                    <p>3. Quotations must be delivered on or before the <strong>stated canvassing date/time at {{$data['venue']}}</strong> . All quotations must be accompanied by eligibility documents as required.</p>
                    <p>4. RFQ will be opened in the presense of the Suppliers or their representative/s who choose to attend at the address stated above. Late quotations shall not be accepted.</p>
                    <p>5. The AFP reserves the right to reject any or all proposals, or declare a failure of canvass, or not award the contract, without thereby incurring any liability in accordance with the Republic Act No. 9184 and its Revised Implementing Rules and Regulations.</p>
                    <p>6. For inquiries, interested parties may contact the Secretariat,  Canvass/Contracting Committee at telephone No.</p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td width="45%"></td>
                            <td width="10%"></td>
                            <td width="45%">
                                <table class="signatory">
                                    <tr>
                                        <td nowrap><strong>{{$data['signatories']->name}}</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>{{$data['signatories']->ranks}} {{$data['signatories']->branch}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{$data['signatories']->designation}}</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>