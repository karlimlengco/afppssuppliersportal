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
                    <div class="printable-form__head__letterhead">
                        <span class="printable-form__head__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span>
                        <span class="printable-form__head__letterhead__details">
                            <strong>302nd Contracting Office</strong><br>
                            Armed Forces of the Philippines Procurement Service<br>
                            Naval Base Pascual Ledesma<br>
                            Fort San Felipe, Cavite City Philippines
                        </span>
                    </div>
                </div>
                <br>
                <!-- form content -->
                <div class="printable-form__body">

                    <span class="printable-form__body__title">Invitation to Submit a Quotation / Proposal</span>
                    <p>{{\Carbon\Carbon::createFromFormat('Y-m-d',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>1. The Armed Forces of the Philippines through the 302ND  Canvass/Contracting Committee invites PhilGEPS registered suppliers to submit quotation/proposal for the following list of procurement with corresponding Approved Budget for the Contract (ABC).</p>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="align-center" width="10%">ITEM NO</td>
                            <td class="align-center" width="10%">UPR NO</td>
                            <td class="align-center" width="20%">RFQ NO</td>
                            <td class="align-center" width="10%">MP</td>
                            <td class="align-center" width="30%">ITEM DESCRIPTION</td>
                            <td class="align-center" width="10%">ABC</td>
                            <td class="align-center" width="20%">CANVASSING DATE/TIME</td>
                        </tr>
                        @foreach($data['quotations'] as $key => $quotation)
                            <tr>
                                <td style="text-align:center">{{$key+1}}</td>
                                <td>{{$quotation->upr_number}}</td>
                                <td>{{$quotation->rfq_number}}</td>
                                <td>@if($quotation->upr) @if($quotation->upr->modes) {{$quotation->upr->modes->name}} @endif @endif</td>
                                <td style="text-align:left">{{$quotation->description}}</td>
                                <td class="align-right">{{formatPrice($quotation->total_amount)}}</td>
                                <td>{{$quotation->canvassing_date}} {{$quotation->canvassing_time}}</td>
                            </tr>
                        @endforeach
                    </table>

                    <p>2. Interested Suppliers may obtain further information from Canvass Contracting Committee Secretariat at the office of 302ND Contracting Office, {{$data['venue']}} for the issuance of Request for Quotation (RFQ) from <strong>8:00 AM - 5:00 PM Monday to Friday excluding Saturdays, Sundays and Holidays</strong>.</p>
                    <p>3. Quotations must be delivered on or before the <strong>stated canvassing date/time at GHQ HEAD OFFICE</strong> . All quotations must be accompanied by eligibility documents as required.</p>
                    <p>4. RFQ will be opened in the presense of the Suppliers or their representative/s who choose to attend at the address stated above. Late quotations shall not be accepted.</p>
                    <p>5. The AFP reserves the right to reject any or all proposals, or declare a failure of canvass, or not award the contract, without thereby incurring any liability in accordance with the Republic Act No. 9184 and its Revised Implementing Rules and Regulations.</p>
                    <p>6. For inquiries, interested parties may contact the Secretariat, Canvass/Contracting Committee at telephone No.</p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="10%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td nowrap><strong>{{$data['signatories']->name}}</strong></td>
                                            <td width="100%"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatories']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="100%"></td>
                                        </tr>
                                        <tr>
                                            <td class="v-align-bottom" height="20px">{{$data['signatories']->designation}}</td>
                                            <td width="100%" height="20px"></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                    {{-- <span class="printable-form__body__title">Invitation to Submit a Quotation / Proposal</span>
                    <p>{{\Carbon\Carbon::createFromFormat('Y-m-d',$data['transaction_date'])->format('d F Y')}}</p>
                    <p>1. The Armed Forces of the Philippines through the 302ND  Canvass/Contracting Committee invites PhilGEPS registered suppliers to submit quotation/proposal for the following list of procurement with corresponding Approved Budget for the Contract (ABC).</p>
                    <table class="printable-form__body__table">
                        <thead>
                            <tr>
                                <th style="text-align:center" class="head" width="10%">ITEM NO</th>
                                <th class="head" width="20%">UPR NO</th>
                                <th class="head" width="20%">RFQ NO</th>
                                <th class="head" width="10%">MP</th>
                                <th class="head" width="30%">ITEM DESCRIPTION</th>
                                <th class="head" width="10%">ABC</th>
                                <th class="head" width="20%">CANVASSING DATE/TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['quotations'] as $key => $quotation)
                                <tr>
                                    <td style="text-align:center">{{$key+1}}</td>
                                    <td>{{$quotation->upr_number}}</td>
                                    <td>{{$quotation->rfq_number}}</td>
                                    <td>@if($quotation->upr) @if($quotation->upr->modes) {{$quotation->upr->modes->name}} @endif @endif</td>
                                    <td style="text-align:left">{{$quotation->description}}</td>
                                    <td>{{formatPrice($quotation->total_amount)}}</td>
                                    <td>{{$quotation->canvassing_date}} {{$quotation->canvassing_time}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>2. Interested Suppliers may obtain further information from Canvass Contracting Committee Secretariat at the office of 302ND Contracting Office,  Naval Base Pascual Ledesma Fort San Felipe, Cavite City Philippines for the issuance of Request for Quotation (RFQ) from 8:00 AM - 5:00 PM Monday to Friday excluding Saturdays, Sundays and Holidays.</p>
                    <p>3. Quotations must be delivered on or before the stated canvassing date/time at GHQ HEAD OFFICE . All quotations must be accompanied by eligibility documents as required.</p>
                    <p>4. RFQ will be opened in the presense of the Suppliers or their representative/s who choose to attend at the address stated above. Late quotations shall not be accepted.</p>
                    <p>5. The AFP reserves the right to reject any or all proposals, or declare a failure of canvass, or not award the contract, without thereby incurring any liability in accordance with the Republic Act No. 9184 and its Revised Implementing Rules and Regulations.</p>
                    <p>6. For inquiries, interested parties may contact the Secretariat,  Canvass/Contracting Committee at telephone No.</p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless" style="page-break-inside:avoid">
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="10%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                            <td width="5%"></td>
                            <td class="signatory align-center v-align-middle" width="30%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['signatories']->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">
                                                <div class="signatory-rank-justify">
                                                    <strong>{{$data['signatories']->ranks}}</strong>
                                                    <span></span>
                                                </div>
                                            </td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['signatories']->designation}}<br>
                            </td>
                        </tr>
                    </table> --}}
                </div>
            </div>

        </div>

    </body>
</html>