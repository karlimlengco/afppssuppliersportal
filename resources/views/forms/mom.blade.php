<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

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
                <!-- form content -->
                <div class="printable-form__body">
                    <span class="printable-form__body__title">Minutes of Meeting</span>
                    <span class="printable-form__body__subtitle">Opening of Canvass Proposal for the procurement of NLC Requirements</span>

                    <p><strong>I.  DATE/TIME AND VENUE:</strong></p>
                    <p>{{$data['date_opened']}}{{$data['time_opened']}} {{$data['venue']}} </p>
                    <p><strong>II. PRESENT:</strong> </p>
                    <p>
                        <strong>Presiding Officer:</strong><br>
                        {{$data['officer']->name}} {{$data['officer']->ranks}}- {{$data['officer']->designation}}<br>
                        <strong>Members:</strong><br>
                        @foreach($data['members'] as $member)
                        {{$member->signatory->name}} {{$member->signatory->ranks}} - {{$member->signatory->designation}}<br>
                        @endforeach
                        <strong>Other Attendees:</strong>
                    </p>
                    <p><strong>III. CALL TO ORDER:</strong></p>
                    <p>The Chairman at around 0900H called the meeting to order. The Secretary was asked as to the quorum and latter informed the attendees that there was a quorum with five (5) regular members present. </p>
                    <p>He then welcomed all the participants. At exactly 0900H, he directed the secretary to close the price quotation box. The secretary gave a short briefing on the procedures on the opening of canvass proposal and stressed the guidelines in Shopping / Negotiated procedure and it will be guided strictly by the provision of IRR-A 9184 and pertinent AFP Logistics Directives and enumerated the requirements for eligibility. </p>
                    <p><strong>IV. AGENDA: Submission and Opening of Canvass Proposals</strong></p>
                    <p>The Secretary then read the agenda of the meeting that was the submission and opening of canvass proposal for procuring PMC requirements. </p>
                    <p><strong>V. OPENING, EXAMINATION AND EVALUATION OF ELIGIBILITY DOCUMENTS</strong> </p>
                    <p>The Chairman and Member in the presence of the attendees checked the submitted eligibility documents of each proponent base on the checklist of eligibility documents using a non-discretionary "Passed/Failed". After examination, the results are as follow: </p>


                    @foreach($data['canvass'] as $canvass)
                    <table class="printable-form__body__table">
                        <thead>
                            <tr>
                                <th width="20%">CANVASS PROPOSAL NR</th>
                                <th width="40%">PROPONENT NAME</th>
                                <th width="20%">ELIGIBILITY REQUIREMENTS </th>
                                <th width="20%">PRICE QUOTATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($canvass->canvass->rfq->proponents as $proponent)
                                <tr>
                                    <td>{{$canvass->canvass->rfq_number}}</td>
                                    <td>{{$proponent->supplier->name}}</td>
                                    <td>{{ucfirst($proponent->status)}}</td>
                                    <td>{{formatPrice($proponent->bid_amount)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>That after reading the price quotation submitted of the proponent for <strong>{{$canvass->canvass->rfq_number}}</strong>, <strong>{{$canvass->canvass->winners->awarder->name}}</strong> moved <strong>{{$canvass->canvass->winners->winner->supplier->name}}</strong> be declared as the winner for having the lowest price among other bidder. <strong>{{$canvass->canvass->winners->seconder->name}}</strong> seconded it. Since no objection was raised, the Chairman declared <strong>{{$canvass->canvass->winners->winner->supplier->name}}</strong> as the winner.</p>
                    @endforeach

                </div>
                <!-- form footer -->
           {{--      <div class="printable-form__foot">
                    <table class="printable-form__foot__table">
                        <tr>
                            <td colspan="2">
                                <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="printable-form__foot__ref">302ND-NLC-SPOF-016-15 111685 281033H December 2015</span>
                            </td>
                            <td>
                                <span class="printable-form__foot__code"><img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                            </td>
                        </tr>
                    </table>
                </div> --}}
            </div>



            <!-- mom.xps -->
            <div class="printable-form">
                <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form content -->
                <div class="printable-form__body no-letterhead">

                    <p><strong>VI. ADJOURNMENT:</strong> </p>
                    <p>The Chairman thanked the attendees and declared the meeting adjourned at exactly 1036H</p>
                    <!-- form signatories -->
                    <table class="printable-form__body__table
                                  printable-form__body__table--borderless">
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="10%" height="80px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="80px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['members'][0]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">{{$data['members'][0]->signatory->ranks}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['members'][0]->signatory->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="60px"></td>
                            <td class="v-align-bottom align-center" width="10%" height="60px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="60px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['members'][1]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">{{$data['members'][1]->signatory->ranks}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['members'][1]->signatory->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['members'][2]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">{{$data['members'][2]->signatory->ranks}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['members'][2]->signatory->designation}}
                            </td>
                        </tr>
                        <tr>
                            <td class="v-align-bottom align-center" width="45%" height="60px"</td>
                            <td class="v-align-bottom align-center" width="10%" height="60px"></td>
                            <td class="v-align-bottom align-center" width="45%" height="60px"</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['members'][3]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">{{$data['members'][3]->signatory->ranks}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['members'][3]->signatory->designation}}
                            </td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-middle" width="45%">
                                <div class="signatory-name">
                                    <table>
                                        <tr>
                                            <td width="50%"></td>
                                            <td nowrap>{{$data['members'][4]->signatory->name}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td class="align-justify">{{$data['members'][4]->signatory->ranks}}</td>
                                            <td width="50%"></td>
                                        </tr>
                                    </table>
                                </div>
                                {{$data['members'][4]->signatory->designation}}
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- form footer -->
                {{-- <div class="printable-form__foot">
                    <table class="printable-form__foot__table">
                        <tr>
                            <td colspan="2">
                                <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="printable-form__foot__ref">302ND-NLC-SPOF-016-15 111685 281033H December 2015</span>
                            </td>
                            <td>
                                <span class="printable-form__foot__code"><img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                            </td>
                        </tr>
                    </table>
                </div> --}}
            </div>

        </div>

    </body>
</html>