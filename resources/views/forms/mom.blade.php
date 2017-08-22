<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

    </head>

    <body>

        <div class="printable-form-wrapper">
            <div class="printable-form">
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span>
                        <span class="printable-form__letterhead__details">
                            <strong>{{$data['header']->name}}</strong><br>
                            Armed Forces of the Philippines Procurement Service<br>
                            {{$data['header']->address}}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">Minutes of Meeting</span>
                    <!-- content -->
                    <span class="printable-form__body__subtitle">Opening of Canvass Proposal for the procurement of NLC Requirements</span>

                    <p><strong>I. DATE/TIME AND VENUE:</strong></p>
                    <p class="indent">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s' ,$data['date_opened']." ".$data['time_opened'])->format('dHi M Y')}}
                        {{$data['venue']}}</p>
                    <p><strong>II. PRESENT:</strong> </p>
                    <table class="printable-form__body__table no-border">
                        <tr>
                            <td width="50px"></td>
                            <td colspan="4"><strong>Presiding Officer:</strong></td>
                        </tr>
                        <tr>
                            <td width="50px"></td>
                            <td width="50px"></td>
                            <td class="align-left" width="35%">{{$data['officer']->ranks}} {{$data['officer']->name}} {{$data['officer']->branch}}</td>
                            <td class="align-left" width="100px">{{$data['officer']->designation}}</td>
                            <td width="5%"></td>
                        </tr>
                        <tr>
                            <td width="50px"></td>
                            <td colspan="4"><strong>Members:</strong></td>
                        </tr>
                        @foreach($data['members'] as $member)
                            <tr>
                                <td width="50px"></td>
                                <td width="50px"></td>
                                <td class="align-left" width="300px">{{$member->signatory->ranks}} {{$member->signatory->name}} {{$member->signatory->branch}}</td>
                                <td class="align-left" width="100px">{{$member->signatory->designation}}</td>
                                <td width="40%"></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td width="50px"></td>
                            <td colspan="4"><strong>Other Attendees:</strong></td>
                        </tr>

                        <tr>
                            <td width="50px"></td>
                            <td class="align-left" width="300px"> {{$data['others']}}</td>
                            <td class="align-left" width="100px"></td>
                            <td width="40%"></td>
                        </tr>
                    </table>
                    <p><strong>III. CALL TO ORDER:</strong></p>
                    <p class="indent-first-line">The Chairman at around {{\Carbon\Carbon::createFromFormat('H:i:s',$data['time_opened'])->format('Hi')}}H called the meeting to order. The Secretary was asked as to the quorum and latter informed the attendees that there was a quorum with regular members present. </p>
                    <p class="indent-first-line" style="text-align:justify">He then welcomed all the participants. At exactly {{\Carbon\Carbon::createFromFormat('H:i:s',$data['time_opened'])->format('Hi')}}H, he directed the secretary to close the price quotation box. The secretary gave a short briefing on the procedures on the opening of canvass proposal and stressed the guidelines in Shopping / Negotiated procedure and it will be guided strictly by the provision of IRR-A 9184 and pertinent AFP Logistics Directives and enumerated the requirements for eligibility. </p>
                    <p style="text-indent: justify; text-align:justify;"><strong>IV. AGENDA: SUBMISSION AND OPENING OF CANVASS PROPOSALS</strong></p>
                    <p class="indent-first-line">The Secretary then read the agenda of the meeting that was the submission and opening of canvass proposal for procuring {{$data['center']}} requirements. </p>
                    <p style="text-indent: justify; text-align:justify;"><strong>V. OPENING, EXAMINATION AND EVALUATION OF ELIGIBILITY DOCUMENTS</strong> </p>
                    <p class="indent-first-line">The Chairman and Member in the presence of the attendees checked the submitted eligibility documents of each proponent base on the checklist of eligibility documents using a non-discretionary "Passed/Failed". After examination, the results are as follow: </p>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-middle" width="20%"><strong>CANVASS PROPOSAL NR</strong></td>
                            <td class="align-middle" width="40%"><strong>PROPONENT NAME</strong></td>
                            <td class="align-middle" width="20%"><strong>ELIGIBILITY REQUIREMENTS</strong></td>
                            <td class="align-middle" width="20%"><strong>PRICE QUOTATION</strong></td>
                        </tr>
                        @foreach($data['canvass']->rfq->proponents as $proponent)
                            <tr>
                                <td>{{$data['canvass']->rfq_number}}</td>
                                <td>{{$proponent->supplier->name}}</td>
                                <td>{{ucfirst($proponent->status)}}</td>
                                <td>{{formatPrice($proponent->bid_amount)}}</td>
                            </tr>
                        @endforeach
                    </table>

                    <p>That after reading the price quotation submitted of the proponent for {{$data['canvass']->rfq_number}}, {{$data['canvass']->winners->awarder->name}} be declared as the winner for having the lowest price among other bidder. {{$data['canvass']->winners->seconder->name}} seconded it. Since no objection was raised, the Chairman declared {{$data['canvass']->winners->awarder->name}} as the winner.</p>
                    <p>Resolution: {{$data['resolution']}}</p>
                    <p><strong>VI. ADJOURNMENT:</strong> </p>
                    <p class="indent-first-line">The Chairman thanked the attendees and declared the meeting adjourned at exactly {{\Carbon\Carbon::createFromFormat('H:i:s',$data['time_closed'])->format('Hi')}}H</p>
                    <table class="printable-form__body__table no-border no-padding" style="page-break-inside:avoid">
                        <tr>
                            <td class="align-bottom align-left" width="45%" height="80px">
                                <strong>{{$data['members'][0]->signatory->ranks}} {{$data['members'][0]->signatory->name}} {{$data['members'][0]->signatory->branch}}</strong><br>
                                {{$data['members'][0]->signatory->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="align-bottom" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-left" height="80px">
                                <strong>{{$data['members'][1]->signatory->ranks}} {{$data['members'][1]->signatory->name}} {{$data['members'][1]->signatory->branch}}</strong><br>
                                {{$data['members'][1]->signatory->designation}}
                            </td>
                            <td></td>
                            <td class="align-bottom align-left" height="80px">
                                <strong>{{$data['members'][2]->signatory->ranks}} {{$data['members'][2]->signatory->name}} {{$data['members'][2]->signatory->branch}}</strong><br>
                                {{$data['members'][2]->signatory->designation}}
                            </td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-left" height="80px">
                                <strong>{{$data['members'][3]->signatory->ranks}} {{$data['members'][3]->signatory->name}} {{$data['members'][3]->signatory->branch}}</strong><br>
                                {{$data['members'][3]->signatory->designation}}
                            </td>
                            <td></td>
                            <td class="align-bottom align-left" height="80px">
                                @if($data['members'][4] != null)
                                <strong>{{$data['members'][4]->signatory->ranks}} {{$data['members'][4]->signatory->name}} {{$data['members'][4]->signatory->branch}}</strong><br>
                                {{$data['members'][4]->signatory->designation}}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </body>
</html>