<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
        {{-- <link rel="stylesheet" href="/fonts/Nunito_Sans/css/nunitosans.css"> --}}
        {{-- <link rel="stylesheet" href="/css/main.css"> --}}
        <style type="text/css">
            body{
                margin:0;
                padding:0;
            }
            @page{
                margin:0;
                padding:0;
            }
        </style>
    </head>

    <body>

            <div class="printable-form">
                <!-- page info -->
                {{-- <span class="printable-form__filename">rfq.xps p 1/1</span> --}}

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
                    <span class="printable-form__body__title">REQUEST FOR QUOTATION</span>

                    <div class="row">
                        <div class="six columns"><p>{{$data['transaction_date']}}</p></div>
                        <div class="six columns align-right"><p>RFQ No: {{$data['rfq_number']}}</p></div>
                    </div>

                    <p>SIR/MADAME,</p>
                    <p>Please quote your lowest price on the items listed below, subject to the following conditions and submit your quotation duly signed by you or your representative not later than {{$data['opening_time']}} {{$data['deadline']}}</p>

                    <ol>
                        <li>DELIVERY PERIOD IS ATLEAST SEVEN (7) CALENDAR DAYS AT GHQ HEADQUARTERS</li>
                        <li>WARRANTY SHALL BE FOR THE PERIOD OF THREE (3) MONTHS FOR SUPPLIES & MATERIALS, ONE (1) YEAR FOR EQUIPMENT, FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY OR PRODUCT WARRANTY WHICHEVER IS LONGER.</li>
                        <li>PRICE VALIDITY SHALL BE FIXED DURING THE BIDDERS PERFORMANCE OF THE CONTRACT AND NOT SUBJECT TO VARIATION OR PRICE ESCALATION ON ANY ACCOUNT.</li>
                        <li>PHILGEPS REGISTRATION SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</li>
                        <li>BIDDERS SHALL SUBMIT ORIGINAL DOCUMENTS SHOWING CERTIFICATIONS OF THE PROJECT BEING OFFERED OR ITS EQUIVALENT, IF NECESSARY.</li>
                        <li>APPROVED BUDGET FOR THE CONTRACT IS: <span style="text-transform:uppercase">{{translateToWords($data['total_amount'])}}</span> PESOS ONLY. ( Php {{ formatPrice($data['total_amount'])}})</li>
                    </ol>
                    <table class="printable-form__body__table">
                        <tr>
                            <td>ITEM NO</td>
                            <td>QTY</td>
                            <td>UOM</td>
                            <td>DESCRIPTION</td>
                            <td>UNIT PRICE</td>
                            <td>TOTAL PRICE</td>
                        </tr>
                        @foreach($data['items'] as $key => $item)
                            <tr>
                                <td>{{$key++}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{$item->unit_measurement}}</td>
                                <td>{{$item->item_description}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">x-x-x-x-x Nothing Follows x-x-x-x-x</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5">TOTAL PhP</td>
                            <td></td>
                        </tr>
                    </table>
                    <p>After having carefully read and accepted your General Conditions, I/We hereby certify the item/s quoted is of the quality/standard at the price/s noted above.</p>

                    <div class="signatory">
                        <table class="signatory__table">
                            <tr>
                                <td>
                                    <div class="conforme no-head">
                                        <div class="conforme__details">
                                            <span class="conforme__details__label">Signature Over Printed Name</span>
                                        </div>
                                        <div class="conforme__details">
                                            <span class="conforme__details__label">Business Name</span>
                                        </div>
                                        <div class="conforme__details">
                                            <span class="conforme__details__label">Business Address</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="conforme no-head">
                                        <div class="conforme__details">
                                            <span class="conforme__details__label">Tel No / Cell No</span>
                                        </div>
                                        <div class="conforme__details">
                                            <span class="conforme__details__label">Date</span>
                                        </div>
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
                                <span class="printable-form__foot__ref">{{$data['upr_number']}} {{$data['transaction_date']}}</span>
                            </td>
                            <td>
                                <span class="printable-form__foot__code">
                                    <img src="{{base_path('public/img/barcode.png')}}" alt="">
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

    </body>
</html>