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
        <!-- noa.xps -->
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
                <span class="printable-form__body__title">NOTICE OF AWARD</span>

                <p>{{$data['canvass_date']}}</p>
                <p>
                    {{$data['supplier']->owner}}<br>
                    {{$data['supplier']->name}}<br>
                    {{$data['supplier']->address}}<br>
                    Tel: {{$data['supplier']->phone_1}} Fax: {{$data['supplier']->fax_1}}
                </p>
                <p>Dear Madam,</p>
                <p>We are pleased to notify you that your price offer/s for the procurement of One Hundred (100) BOND PAPER A4 only of RFQ No. {{$data['rfq_number']}} dated {{$data['transaction_date']}} for the use of {{$data['unit']}} in the amount of {{translateToWords($data['total_amount'])}} PESOS ONLY (Php {{formatPrice($data['total_amount'])}}) is hereby accepted. A contract for the procurement of this item/s is being awarded to your company/firm.</p>
                <p>You shall subsequently be informed (thru your contact number or email address specified below) accordingly to appear before this office for the requirements.</p>
                <ul>
                    <li>Signing of PURCHASE ORDER</li>
                    <li>Acknowledgement of the Notice to Proceed</li>
                </ul>
                <p>Failure to comply with the above requirements within the prescribed period shall be grounds for the cancellation of this award.</p>
                <p>Meantime, you are requested to affix your conformity hereto and send this back to us not later than two (2) working days upon receipt of this notice. </p>
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
                            <td></td>
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
                            <span class="printable-form__foot__ref">{{$data['rfq_number']}} 111685 {{$data['transaction_date']}}</span>
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




        <!-- noa.xps -->
        <div class="printable-form">
            <!-- page info -->

            <!-- main page -->
            <div class="printable-form__head">
                <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
            </div>

            <div class="printable-form__body no-letterhead">

                <div class="signatory">
                    <table class="signatory__table">
                        <tr>
                            <td></td>
                            <td>
                                <div class="conforme">
                                    <span class="conforme__head">Conforme:</span>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">Signature Over Printed Name</span>
                                    </div>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">Date</span>
                                    </div>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">Contact No.</span>
                                    </div>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">Email Address</span>
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
                            <span class="printable-form__foot__ref">{{$data['rfq_number']}} 111685 {{$data['transaction_date']}}</span>
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