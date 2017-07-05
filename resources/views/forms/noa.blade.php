<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
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
                <!-- form header -->
                <div class="printable-form__body">
                    <span class="printable-form__body__title">Notice of Award</span>

                    <p>{{$data['transaction_date']}}</p>
                    <p>
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}<br>
                        Tel: {{$data['supplier']->phone_1}} Fax: {{$data['supplier']->fax_1}}
                    </p>
                    <p>Dear Madam,</p>
                    <p>We are pleased to notify you that your price offer/s for the procurement of {{$data['project_name']}} only of RFQ No. {{$data['rfq_number']}} dated {{$data['rfq_date']}} for the use of {{$data['unit']}} in the amount of {{translateToWords($data['bid_amount'])}} PESOS ONLY (Php {{formatPrice($data['bid_amount'])}}) is hereby accepted. A contract for the procurement of this item/s is being awarded to your company/firm.</p>
                    <p>You shall subsequently be informed (thru your contact number or email address specified below) accordingly to appear before this office for the requirements.</p>
                    <ul>
                        <li>Signing of PURCHASE ORDER</li>
                        <li>Acknowledgement of the Notice to Proceed</li>
                    </ul>
                    <p>Failure to comply with the above requirements within the prescribed period shall be grounds for the cancellation of this award.</p>
                    <p>Meantime, you are requested to affix your conformity hereto and send this back to us not later than two (2) working days upon receipt of this notice. </p>
                    <p>Very truly yours;</p>
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
                                <span class="signatory-name">{{$data['signatory']->name}}</span>
                                {{$data['signatory']->designation}}</td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-middle" width="45%" height="50px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-left v-align-middle" width="45%" height="50px">CONFORME:</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="20px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="20px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="10px">Full Name / Rank</td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Signature over Printed Name</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Date</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Contact Number</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center v-align-bottom" width="45%" height="30px"></td>
                        </tr>
                        <tr>
                            <td class="signatory align-center" width="45%"></td>
                            <td width="10%"></td>
                            <td class="signatory align-center" width="45%">
                                <span class="conforme-label">Email Address</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- form footer -->
                <div class="printable-form__foot">
                    <table class="printable-form__foot__table">
                        <tr>
                            <td colspan="2">
                                <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="printable-form__foot__ref">{{$data['rfq_number']}} {{$data['transaction_date']}}</span>
                            </td>
                            <td>
                                <span class="printable-form__foot__code"><img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </body>
</html>