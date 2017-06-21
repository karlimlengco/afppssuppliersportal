<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
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
        <!-- ntp.xps -->
        <div class="printable-form">
            <!-- page info -->

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
                <span class="printable-form__body__title">NOTICE TO PROCEED</span>

                <p>{{$data['transaction_date']}}</p>
                <p>
                    {{$data['supplier']->owner}}<br>
                    {{$data['supplier']->name}}<br>
                    {{$data['supplier']->address}}<br>
                </p>
                <p>Dear Madam,</p>
                <p>Please be informed that the PURCHASE ORDER No. {{$data['po_number']}} dated {{$data['transaction_date']}} prepared in your favor for the procurement of One Hundred (100) BOND PAPER A4 only amounting to {{translateToWords($data['total_amount'])}} (Php  {{formatPrice($data['total_amount'])}}) ONLY is hereby approved.</p>
                <p>May we request you or your authorized representative to receive this notice and the approved PURCHASE ORDER not later than seven (7) calendar days reckoned from the date of this notice.</p>
                <p>Furthermore, be reminded that failure on your part to receive document on the specified date is ground for cancellation of the aforesaid PURCHASE ORDER.</p>
                <p>Very truly yours;</p>

                <div class="signatory">
                    <table class="signatory__table">
                        <tr>
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatory']->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatory']->designation}}</span>
                                </div>
                            </td>
                            <td>
                                <div class="conforme">
                                    <span class="conforme__note">I acknowledge receipt of this Notice on:</span>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">Date</span>
                                    </div>
                                    <span class="conforme__note">Name of the Representative of the Bidder:</span>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">Authorized Signature Over Printed Name</span>
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
                            <span class="printable-form__foot__ref">{{$data['po_number']}} 111685 {{$data['transaction_date']}}</span>
                        </td>
                        <td>
                            <span class="printable-form__foot__code">
                            <img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </body>
</html>