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

        <!-- terms-and-condition-po.xps p1 -->
        <div class="printable-form">
            <!-- main page -->
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
                <span class="printable-form__body__title">NOTICE OF DELIVERY / REQUEST FOR INSPECTION</span>

                <p>{{$data['today']}}</p>
                <p>To: All Concerned</p>
                <ol>
                    <li>Reference: Approved PURCHASE ORDERNo. {{$data['po_number']}}</li>
                    <li>Per reference above, {{$data['winner']}} will be delivering {{$data['project_name']}} only at {{$data['center']}} on {{$data['expected_date']}} in the total amount of {{translateToWords($data['bid_amount'])}}    (Php{{formatPrice($data['bid_amount'])}}) ONLY. </li>
                    <li>Request acknowledge receipt. </li>
                </ol>

                <div class="signatory">
                    <table class="signatory__table">
                        <tr>
                            <td>
                                <div class="conforme">
                                    <span class="conforme__head">Distributions:</span>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">C, TIAC</span>
                                    </div>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">COA</span>
                                    </div>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">MFO</span>
                                    </div>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">SAO</span>
                                    </div>
                                    <div class="conforme__details">
                                        <span class="conforme__details__label">Recipient Unit</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="signatory__details">
                                    <span class="signatory__details__name">{{$data['signatory']->name}}</span>
                                    <span class="signatory__details__position">{{$data['signatory']->designation}}</span>
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