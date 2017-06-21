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
        <div class="printable-form">
            <!-- main page -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                <div class="printable-form__head__letterhead">
                    <span class="printable-form__head__letterhead__logo">
                        <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                    </span>
                    <span class="printable-form__head__letterhead__details">
                        <strong>302ND Contracting Office</strong><br>
                        ARMED FORCES OF THE PHILIPPINES PROCUREMENT SERVICE<br>
                        Naval Base Pascual Ledesma<br>
                        Fort San Felipe, Cavite City Philippines
                    </span>
                </div>
            </div>

            <div class="printable-form__body">
                <span class="printable-form__body__title">INVITATION TO SUBMIT A QUOTATION / PROPOSAL</span>

                <p>{{$data['transaction_date']}}</p>
                <p>1. The Armed Forces of the Philippines through the 302ND  Canvass/Contracting Committee invites PhilGEPS registered suppliers to submit quotation/proposal for the following list of procurement with corresponding Approved Budget for the Contract (ABC).</p>
                <table class="printable-form__body__table">
                    <tr>
                        <td>ITEM NO</td>
                        <td>RFQ NO</td>
                        <td>MP</td>
                        <td>ITEM DESCRIPTION</td>
                        <td>ABC</td>
                        <td>CANVASSING DATE/TIME</td>
                    </tr>
                    @foreach($data['quotations'] as $key => $quotation)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$quotation->rfq_number}}</td>
                        <td>NEGO</td>
                        <td>{{$quotation->description}}</td>
                        <td>{{formatPrice($quotation->total_amount)}}</td>
                        <td>{{$quotation->canvassing_date}} {{$quotation->canvassing_time}}</td>
                    </tr>
                    @endforeach
                </table>
                <p>2. Interested Suppliers may obtain further information from Canvass Contracting Committee Secretariat at the office of 302ND Contracting Office,  Naval Base Pascual Ledesma Fort San Felipe, Cavite City Philippines for the issuance of Request for Quotation (RFQ) from 8:00 AM - 5:00 PM Monday to Friday excluding Saturdays, Sundays and Holidays.</p>
                <p>3. Quotations must be delivered on or before the stated canvassing date/time at GHQ HEAD OFFICE . All quotations must be accompanied by eligibility documents as required.</p>
                <p>4. RFQ will be opened in the presense of the Suppliers or their representative/s who choose to attend at the address stated above. Late quotations shall not be accepted.</p>
                <p>5. The AFP reserves the right to reject any or all proposals, or declare a failure of canvass, or not award the contract, without thereby incurring any liability in accordance with the Republic Act No. 9184 and its Revised Implementing Rules and Regulations.</p>
                <p>6. For inquiries, interested parties may contact the Secretariat,  Canvass/Contracting Committee at telephone No.</p>

                <div class="signatory">
                    <table class="signatory__table">
                        <tr>
                            <td></td>
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatories']->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatories']->designation}}</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="printable-form__foot">
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
                            <img src="{{base_path('public/img/barcode.png')}}" alt="">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>